<?php
class DatabaseConnection
{
  /** @var \DatabaseConnection */
  private static $dbConnectionInstance;

  /** @var \mysqli */
  private $mysqlConnection;

  /**
   * This is the constructor. This is Singleton.
   * @param string $server Address of the mysql server.
   * @param string $port port which mysql server is listening.
   * @param string $username mysql username.
   * @param string $password mysql password.
   * @param string $database mysql database name.
   * @return void
   */
  private function __construct(string $server, string $port, string $username, string $password, string $database)
  {
    try {
      $this->mysqlConnection = new mysqli($server, $username, $password, $database, $port);
      mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

      /* check connection */
      if ($this->mysqlConnection->connect_errno || !$this->mysqlConnection->ping()) {
        $this->mysqlConnection = null;
      }
    } catch (Exception $e) {
      $this->mysqlConnection = null;
    }
  }

  /**
   * Creates a DatabaseConnection if not already exists. If a connection already
   * exists, return the existing connection. This is Singleton.
   * s@return DatabaseConnection|null instance of this class if mysql connection
   * was successfully established, else returns null.
   */
  public static function getConnection(): ?DatabaseConnection
  {
    try {
      require_once('envVars.php');
      if (DatabaseConnection::$dbConnectionInstance == null) {
        $dbConfig = getEnvVars(['DB_HOST', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE']);
        $serverName = $dbConfig['DB_HOST'];
        $username = $dbConfig['DB_USERNAME'];
        $password = $dbConfig['DB_PASSWORD'];
        $database = $dbConfig['DB_DATABASE'];
        $serverAddress = explode(':', $serverName, 2);
        $port = '3306';
        if ($serverAddress && sizeof($serverAddress) == 2) {
          $serverName = $serverAddress[0];
          $port = $serverAddress[1];
        }
        DatabaseConnection::$dbConnectionInstance = new DatabaseConnection($serverName, $port, $username, $password, $database);
      }
      if (DatabaseConnection::$dbConnectionInstance && DatabaseConnection::$dbConnectionInstance->mysqlConnection) {
        return DatabaseConnection::$dbConnectionInstance;
      }
      return null;
    } catch (Exception $e) {
      return null;
    }
  }

  /**
   * Authenticates a user. Check if the provided email and passwords match.
   * @param string $email user's email address.
   * This should match the regex ^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$.
   * @param string $password user's account password.
   * This should match the regex ^[\x21-\x7E]{8,15}$.
   * @return bool if the email and password match, returns true. Otherwise returns
   * false. In case of any error, this returns false to be fail safe.
   */
  public function auth(string $email, string $password): bool
  {
    if (!($this->mysqlConnection instanceof mysqli)) return false;
    if ($this->validate($email, $password)) {
      try {
        $query = 'SELECT password FROM account WHERE email=?';
        $statement = $this->mysqlConnection->prepare($query);
        $statement->bind_param('s', $email);
        $statement->execute();
        $statement->store_result();
        $rowcount = $statement->num_rows;
        if ($rowcount == 1) {
          $statement->bind_result($passwordHash);
          $statement->fetch();
          return password_verify($password, $passwordHash);
        }
        $statement->close();
      } catch (Exception $e) {
        return false;
      }
    }
    return false;
  }

  /**
   * Saves a user account creation request in database. The passwords are hashed with
   * Bcrypt. The requests are temporarily stored in a separate table and user should
   * activate the account using the returned token before the request expires.
   * @param string $email user's email address.
   * This should match the regex ^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$.
   * @param string $password user's account password.
   * This should match the regex ^[\x21-\x7E]{8,15}$.
   * @param int $expire_delay The delay in seconds before the account request expires
   * @return string|null If the account request is saved, returns the randomly generated
   * token to activate the account. If the account already exists, returns '0'. If an
   * error occured, returns null.
   */
  public function requestAccount(string $email, string $password, int $expire_delay): ?string
  {
    if (!($this->mysqlConnection instanceof mysqli)) return null;
    if ($this->validate($email, $password)) {
      ($this->mysqlConnection)->begin_transaction();
      try {
        $queryCheck = 'SELECT email FROM account WHERE email=?';
        $statementCheck = $this->mysqlConnection->prepare($queryCheck);
        $statementCheck->bind_param('s', $email);
        $statementCheck->execute();
        $statementCheck->store_result();
        $rowcount = $statementCheck->num_rows;
        $statementCheck->close();
        if ($rowcount > 0) {
          ($this->mysqlConnection)->rollback();
          return '0';
        }
        $queryInsert = 'INSERT INTO pending_account (email, password, token, expire) VALUES (?, ?, ?, ?);';
        $hashed = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $token = '';
        for ($i = 0; $i < 8; $i++) {
          $token = $token . str_pad(base_convert(rand(1, 0x7fffffff), 10, 36), 6, '0', STR_PAD_LEFT);
        }
        $expire = time() + $expire_delay;
        $statementInsert = $this->mysqlConnection->prepare($queryInsert);
        $statementInsert->bind_param('sssi', $email, $hashed, $token, $expire);
        $status = $statementInsert->execute();
        $statementInsert->close();
        ($this->mysqlConnection)->commit();
        if ($status) {
          return $token;
        } else {
          return null;
        }
      } catch (Exception $e) {
        ($this->mysqlConnection)->rollback();
        return null;
      }
    }
    return null;
  }

  /**
   * Activate the user account if the email and token matches.
   * @param string $email user's email address.
   * This should match the regex ^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$.
   * @param string $token the token to activate the account
   * @return bool if the email and token match, activates the account andreturns true.
   * Otherwise returns false. In case of any error, this returns false.
   */
  public function activateAccount(string $email, string $token): bool
  {
    if (!preg_match('/^[a-z0-9]{45,50}$/', $token)) return false;
    if (!($this->mysqlConnection instanceof mysqli)) return false;
    ($this->mysqlConnection)->begin_transaction();
    try {
      $queryGet = 'SELECT password FROM pending_account WHERE email=? AND token=? AND expire>?';
      $statementGet = $this->mysqlConnection->prepare($queryGet);
      $expire = time();
      $statementGet->bind_param('ssi', $email, $token, $expire);
      $statementGet->execute();
      $statementGet->store_result();
      $rowcount = $statementGet->num_rows;
      if ($rowcount == 1) {
        $statementGet->bind_result($password);
        $statementGet->fetch();
        $statementGet->close();
        $queryInsert = 'INSERT INTO account VALUES (?, ?);';
        $statementInsert = $this->mysqlConnection->prepare($queryInsert);
        $statementInsert->bind_param('ss', $email, $password);
        $status = $statementInsert->execute();
        $statementInsert->close();
        ($this->mysqlConnection)->commit();
        return $status;
      }
    } catch (Exception $e) {
      ($this->mysqlConnection)->rollback();
      return false;
    }
    return false;
  }

  public function closeConnection()
  {
    if (DatabaseConnection::$dbConnectionInstance != null && $this->mysqlConnection instanceof mysqli) {
      $this->mysqlConnection->close();
    }
    $this->__destruct();
  }

  /**
   * Validates an email and password.
   * @param string $email An email address to validate.
   * This should match the regex ^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$.
   * @param string $password An account password.
   * This should match the regex ^[\x21-\x7E]{8,15}$.
   * @return bool if the email and passwords are in valid format, returns true.
   * Otherwise returns false. In case of any exception, this returns false.
   */
  private function validate(string $email, string $password): Bool
  {
    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);
    $email_pattern = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
    $password_pattern = '/^[\x21-\x7E]{8,15}$/';
    //$password_pattern = '/^\S*(?=\S{8,15})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/';
    if (preg_match($email_pattern, $email) && preg_match($password_pattern, $password)) {
      return true;
    }
    return false;
  }

  public function __destruct()
  {
  }
}

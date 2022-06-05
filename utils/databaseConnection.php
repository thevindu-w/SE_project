<?php
class DatabaseConnection
{
  /** @var \DatabaseConnection */
  private static $dbConnectionInstance;

  /** @var \mysqli */
  private $mysqlConnection;

  private function __construct($server, $port, $username, $password, $database)
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

  public function activateAccount(string $email, string $token): bool
  {
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

  private function validate($email, $password): Bool
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

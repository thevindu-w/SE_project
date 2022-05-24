<?php
class DatabaseConn
{
  /** @var \DatabaseConn */
  private static $dbconn;

  /** @var \myslqi */
  private $conn;

  private function __construct($server, $port, $username, $password, $database)
  {
    try {
      $this->conn = new mysqli($server, $username, $password, $database, $port);
      mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

      /* check connection */
      if ($this->conn->connect_errno || !$this->conn->ping()) {
        $this->conn = null;
      }
    } catch (Exception $e) {
      $this->conn = null;
    }
  }

  public static function get_conn(): ?DatabaseConn
  {
    try {
      require_once('envvars.php');
      if (DatabaseConn::$dbconn == null) {
        $dbconfig = getEnvVars(['DB_HOST', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE']);
        $servername = $dbconfig['DB_HOST'];
        $username = $dbconfig['DB_USERNAME'];
        $password = $dbconfig['DB_PASSWORD'];
        $database = $dbconfig['DB_DATABASE'];
        $server_addr = explode(':', $servername, 2);
        $port = '3306';
        if ($server_addr && sizeof($server_addr) == 2) {
          $servername = $server_addr[0];
          $port = $server_addr[1];
        }
        DatabaseConn::$dbconn = new DatabaseConn($servername, $port, $username, $password, $database);
      }
      if (DatabaseConn::$dbconn && DatabaseConn::$dbconn->conn) {
        return DatabaseConn::$dbconn;
      }
      return null;
    } catch (Exception $e) {
      return null;
    }
  }

  public function auth(string $email, string $pw): bool
  {
    if (!($this->conn instanceof mysqli)) return false;
    if ($this->validate($email, $pw)) {
      try {
        $q = 'SELECT password FROM account WHERE email=?';
        $stmt = $this->conn->prepare($q);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $rowcount = $stmt->num_rows;
        if ($rowcount == 1) {
          $stmt->bind_result($password);
          $stmt->fetch();
          return password_verify($pw, $password);
        }
        $stmt->close();
      } catch (Exception $e) {
        return false;
      }
    }
    return false;
  }

  public function requestAccount(string $email, string $pw, int $expire_delay): ?string
  {
    if (!($this->conn instanceof mysqli)) return null;
    if ($this->validate($email, $pw)) {
      ($this->conn)->begin_transaction();
      try {
        $q = 'SELECT email FROM account WHERE email=?';
        $stmt = $this->conn->prepare($q);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $rowcount = $stmt->num_rows;
        $stmt->close();
        if ($rowcount > 0) {
          ($this->conn)->rollback();
          return '0';
        }
        $q2 = 'INSERT INTO pending_account (email, password, token, expire) VALUES (?, ?, ?, ?);';
        $hashed = password_hash($pw, PASSWORD_BCRYPT, ['cost' => 12]);
        $token = '';
        for ($i = 0; $i < 8; $i++) {
          $token = $token . str_pad(base_convert(rand(1, 0x7fffffff), 10, 36), 6, '0', STR_PAD_LEFT);
        }
        $expire = time() + $expire_delay;
        $stmt2 = $this->conn->prepare($q2);
        $stmt2->bind_param('sssi', $email, $hashed, $token, $expire);
        $status = $stmt2->execute();
        $stmt2->close();
        ($this->conn)->commit();
        if ($status) {
          return $token;
        } else {
          return null;
        }
      } catch (Exception $e) {
        ($this->conn)->rollback();
        return null;
      }
    }
    return null;
  }

  public function activateAccount(string $email, string $token): bool
  {
    if (!($this->conn instanceof mysqli)) return false;
    ($this->conn)->begin_transaction();
    try {
      $q = 'SELECT password FROM pending_account WHERE email=? AND token=? AND expire>?';
      $stmt = $this->conn->prepare($q);
      $expire = time();
      $stmt->bind_param('ssi', $email, $token, $expire);
      $stmt->execute();
      $stmt->store_result();
      $rowcount = $stmt->num_rows;
      if ($rowcount == 1) {
        $stmt->bind_result($password);
        $stmt->fetch();
        $stmt->close();
        $q2 = 'insert into account values (?, ?);';
        $stmt2 = $this->conn->prepare($q2);
        $stmt2->bind_param('ss', $email, $password);
        $status = $stmt2->execute();
        $stmt2->close();
        ($this->conn)->commit();
        return $status;
      }
    } catch (Exception $e) {
      ($this->conn)->rollback();
      return false;
    }
    return false;
  }

  public function close_conn()
  {
    if (DatabaseConn::$dbconn != null && $this->conn instanceof mysqli) {
      $this->conn->close();
    }
    $this->__destruct();
  }

  private function validate($email, $pw): Bool
  {
    $email = htmlspecialchars($email);
    $pw = htmlspecialchars($pw);
    $email_pattern = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
    $pw_pattern = '/^[\x21-\x7E]{8,15}$/';
    //$pw_pattern = '/^\S*(?=\S{8,15})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/';
    if (preg_match($email_pattern, $email) && preg_match($pw_pattern, $pw)) {
      return true;
    }
    return false;
  }

  public function __destruct()
  {
  }
}

<?php

/**
 * PDO Database class.
 * Connects to database;
 * Create prepared statements;
 * Bind values;
 * Return rows and results.
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

if (!class_exists('Log')) {
  require_once ROOT_PATH . 'classes/log.class.php';
}

class Database
{
  private $host = DB_HOST;
  private $user = DB_USERNAME;
  private $pass = DB_PASSWORD;
  private $dbname = DB_NAME;

  private $dbh;
  private PDOStatement $stmt;
  private $error;
  private ?Log $logger = null;

  public function __construct()
  {
    // Set DSN
    $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
    $options = array(
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    // Create PDO instance
    try {
      $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
    } catch (PDOException $e) {
      $this->error = $e->getMessage();
      echo $this->error;
    }
  }

  /**
   * Log message onto database
   * 
   * @param string what message to log
   */
  private function log(string $what)
  {
    if (!isset($this->logger) or is_null($this->logger)) {
      $this->logger =
        new Log(array('controller' => 'Database', 'action' => 'PDOException', $this), null, true);
    }
    $this->logger->log($what);
  }

  /**
   * Prepare statement with query.
   * 
   * @param string sql MySQL prepared statement.
   */
  public function query(string $sql): void
  {
    $this->stmt = $this->dbh->prepare($sql);
  }

  /**
   * Bind values to prepared statement.
   * 
   * @param mixed param Parameter name to bind.
   * @param mixed value Value to bind to parameter.
   * @param mixed type Type of the parameter to bind.
   * @throws PDOException if binding fails.
   */
  public function bind($param, $value, $type = null)
  {
    if (is_null($type)) {
      switch (true) {
        case is_int($value):
          $type = PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = PDO::PARAM_NULL;
          break;
        default:
          $type = PDO::PARAM_STR;
      }
    }

    $this->stmt->bindValue($param, $value, $type);
  }

  /**
   * Execute prepared statement.
   * 
   * @return bool Success status.
   *  Exceptions are handled and
   *  saved in `log` table of the database.
   */
  public function execute(): bool
  {
    try {
      return $this->stmt->execute();
    } catch (PDOException $e) {
      $this->error = $e->getMessage();
      $this->log($this->error);
      return false;
    }
  }

  /**
   * Get multiple rows that match the prepared statment.
   * 
   * @return ?array Returns array of stdClass objects
   *  Exceptions are handled and recorded in 
   *  `log` table of the database.
   *  Null is returnd if fails.c
   */
  public function resultSet(): ?array
  {
    try {
      if ($this->execute()) {
        $result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
        if (!is_array($result)) {
          return array($result);
        }
        return $result;
      }
      return null;
    } catch (PDOException $e) {
      $this->error = $e->getMessage();
      $this->log($e->getMessage());
      return null;
    }
  }

  /**
   * Get multiple rows that match the prepared statment.
   * 
   * @return ?stdClass Returns array of stdClass objects
   *  Exceptions are handled and recorded in 
   *  `log` table of the database.
   *  Null is returned if fails.
   */
  public function single(): ?stdClass
  {
    try {
      if ($this->execute()) {
        $result =  $this->stmt->fetch(PDO::FETCH_OBJ);
        if (is_bool($result)) {
          return null;
        }
        return $result;
      }
    } catch (PDOException $e) {
      $this->error = $e->getMessage();
      $this->log($e->getMessage());
      return null;
    }
  }

  /**
   * Get count of updated/inserted/deleted rows.
   * 
   * @return int Row count.
   *  Defaults to `0` if the query fails.
   *  Exceptions are handled and
   *  saved in `log` table of the database.
   */
  public function rowCount(): int
  {
    try {
      return $this->stmt->rowCount();
    } catch (PDOException $e) {
      $this->error = $e->getMessage();
      $this->log($e->getMessage());
      return false;
    }
  }
}
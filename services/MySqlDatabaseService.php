<?php

namespace Melv\Test\Service;

use Melv\Test\Application;
use Melv\Test\Service\Interface\DatabaseService;
use PDO;
use PDOStatement;

class MySqlDatabaseService implements DatabaseService
{
  protected static MySqlDatabaseService $instance;

  public static function getInstance(): MySqlDatabaseService
  {
    static::$instance ??= new static();
    return static::$instance;
  }

  protected readonly PDO $connection;

  public function __construct()
  {
    try {
      $this->connection = new PDO(
        "mysql:host=$_ENV[DB_DSN];dbname=$_ENV[DB_NAME];charset=utf8",
        $_ENV["DB_USER"],
        $_ENV["DB_PASSWORD"],
        [
          PDO::MYSQL_ATTR_SSL_CA => openssl_get_cert_locations()["default_cert_file"],
          PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
        ]
      );
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (\Throwable $e) {
      Application::$instance->handleError($e);
    }
  }

  public function query(string $sql): PDOStatement|false
  {
    return $this->connection->query($sql);
  }

  public function prepare(string $sql): PDOStatement|false
  {
    return $this->connection->prepare($sql);
  }
}

<?php

namespace Melv\Test\Service;

use Melv\Test\Application;
use Melv\Test\Service\Interface\DatabaseService;
use PDO;
use PDOStatement;

class MySqlDatabaseService implements DatabaseService
{
  protected readonly PDO $connection;

  public function __construct(string $dsn, string $dbName, string $username, string $password)
  {
    try {
      $this->connection = new PDO(
        "mysql:host=$dsn;dbname=$dbName;charset=utf8",
        $username,
        $password,
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

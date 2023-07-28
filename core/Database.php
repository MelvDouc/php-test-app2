<?php

namespace Melv\Test;

use PDO;

class Database
{
  public readonly PDO $connection;

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
}

<?php

namespace Melv\Test;

use PDO;

class Database
{
  public readonly PDO $connection;

  public function __construct(string $host, string $dbName, string $user, string $password)
  {
    try {
      $this->connection = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $user, $password);
    } catch (\Throwable $e) {
      echo "<pre style=\"font-family: 'Fira Code', 'Ubuntu Mono', Consolas, 'Courier New', monospace;\">";
      var_dump($e->getMessage());
      echo "</pre>";
      exit;
    }
  }
}

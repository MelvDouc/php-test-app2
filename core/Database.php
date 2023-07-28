<?php

namespace Melv\Test;

use PDO;

class Database
{
  protected const CONNECTION_URI = "/^mysql:\/{2}(?P<username>\w+):(?P<password>\w+)@(?P<dsn>[\w\.]+):\d+\/(?P<dbName>\w+)/";
  public readonly PDO $connection;

  public function __construct(string $connectionUri)
  {
    preg_match(self::CONNECTION_URI, $connectionUri, $detail);
    try {
      $this->connection = new PDO(
        "mysql:host=$detail[dsn];dbname=$detail[dbName];charset=utf8",
        $detail["username"],
        $detail["password"]
      );
    } catch (\Throwable $e) {
      echo "<pre style=\"font-family: 'Fira Code', 'Ubuntu Mono', Consolas, 'Courier New', monospace;\">";
      var_dump($e->getMessage());
      echo "</pre>";
      exit;
    }
  }
}

<?php

use Dotenv\Dotenv;
use Melv\Test\Application;

require __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/controllers/HomeController.php";
require_once __DIR__ . "/models/Person.php";

try {
  Dotenv::createImmutable(__DIR__)->load();
} catch (\Throwable $e) {
  echo "<pre>";
  var_dump($e);
  echo "</pre>";
  exit;
}

$app = new Application(__DIR__);

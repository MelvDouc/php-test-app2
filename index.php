<?php

use Dotenv\Dotenv;
use Melv\Test\Application;

/** @var \Composer\Autoload\ClassLoader */
$autoLoader = require __DIR__ . "/vendor/autoload.php";

$autoLoader->addPsr4("Melv\\Test\\Controller\\", __DIR__ . "/controllers");
$autoLoader->addPsr4("Melv\\Test\\Model\\", __DIR__ . "/models");

try {
  Dotenv::createImmutable(__DIR__)->load();
} catch (\Throwable $e) {
  echo "<pre>";
  var_dump($e);
  echo "</pre>";
  exit;
}

$app = new Application(__DIR__);

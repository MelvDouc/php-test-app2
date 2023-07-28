<?php

use Dotenv\Dotenv;
use Melv\Test\Router;
use Melv\Test\Database;
use Melv\Test\Application;
use Melv\Test\Controller\HomeController;

$ROOT_DIR = dirname(__DIR__);

/** @var \Composer\Autoload\ClassLoader */
$autoLoader = require $ROOT_DIR . "/vendor/autoload.php";
$autoLoader->addPsr4("Melv\\Test\\Controller\\", $ROOT_DIR . "/controllers");
$autoLoader->addPsr4("Melv\\Test\\Model\\", $ROOT_DIR . "/models");

try {
  Dotenv::createImmutable($ROOT_DIR)->load();
} catch (\Throwable $e) {
  echo "<pre>";
  var_dump($e);
  echo "</pre>";
  exit;
}

Application::create($ROOT_DIR);
Application::$instance->setDatabase(
  new Database($_ENV["DB_CONNECTION_URI"])
);

$router = new Router();

$router->get("/", [HomeController::getInstance(), "home"]);
$router->get("/about", [HomeController::getInstance(), "about"]);
$router->get("/profile/:id", [HomeController::getInstance(), "person"]);
$router->get("/(.*)", [HomeController::getInstance(), "_404"]);

Application::$instance->useRouter($router);
Application::$instance->run();

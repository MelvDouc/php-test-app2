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
  $env = $_ENV["PHP_ENV"] ?? null;
  if ($env === null)
    Dotenv::createImmutable($ROOT_DIR)->load();
} catch (\Throwable $e) {
  if ($env === "development") {
    echo "<pre style=\"color: green; font-family: 'Fira Code', 'Ubuntu Mono', Consolas, 'Courier New', monospace;\">";
    var_dump($e->getMessage());
    echo "</pre>";
  }
  exit;
}

Application::create($ROOT_DIR);
Application::$instance->setDatabase(
  new Database($_ENV["DB_DSN"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"])
);

$router = new Router();

$router->get("/", [HomeController::getInstance(), "home"]);
$router->get("/about", [HomeController::getInstance(), "about"]);
$router->get("/profile/:id", [HomeController::getInstance(), "person"]);
$router->get("/(.*)", [HomeController::getInstance(), "_404"]);

Application::$instance->useRouter($router);
Application::$instance->run();

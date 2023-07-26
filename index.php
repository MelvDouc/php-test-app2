<?php

use Dotenv\Dotenv;
use Melv\Test\Application;
use Melv\Test\Database;
use Melv\Test\Router;

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/controllers/HomeController.php";

$router = new Router();
$app = new Application(__DIR__, $router);

$dotenv = Dotenv::createImmutable(Application::$instance->rootDir);
$dotenv->load();

$app->setDatabase(
  new Database($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"])
);

$router->get("/", \Melv\Test\Controller\home::class);
$router->get("/about", \Melv\Test\Controller\about::class);
$router->get("/profile/:id", \Melv\Test\Controller\profile::class);
$router->get("/(.*)", \Melv\Test\Controller\_404::class);
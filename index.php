<?php

use Melv\Test\Application;
use Melv\Test\Database;
use Melv\Test\Router;
use Melv\Test\Controller;

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/controllers/HomeController.php";

try {
  \Dotenv\Dotenv::createImmutable(__DIR__)->load();
} catch (\Throwable $e) {
}

$app = new Application(__DIR__);
$app->setDatabase(
  new Database($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"])
);

$router = new Router();
$router->get("/", Controller\home::class);
$router->get("/about", Controller\about::class);
$router->get("/profile/:id", Controller\person::class);
$router->get("/(.*)", Controller\_404::class);

$app->useRouter($router);

<?php

use Melv\Test\Router;
use Melv\Test\Database;
use Melv\Test\Application;
use Melv\Test\Controller\HomeController;

require_once dirname(__DIR__) . "/index.php";

Application::$instance->setDatabase(
  new Database($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"])
);

$router = new Router();

$router->get("/", [HomeController::getInstance(), "home"]);
$router->get("/about", [HomeController::getInstance(), "about"]);
$router->get("/profile/:id", [HomeController::getInstance(), "person"]);
$router->get("/(.*)", [HomeController::getInstance(), "_404"]);

Application::$instance->useRouter($router);
Application::$instance->run();

<?php

use Melv\Test\Router;
use Melv\Test\Database;
use Melv\Test\Application;
use Melv\Test\Controller;

require_once dirname(__DIR__) . "/index.php";

Application::$instance->setDatabase(
  new Database($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"])
);

$router = new Router();
$router->get("/", Controller\home::class);
$router->get("/about", Controller\about::class);
$router->get("/profile/:id", Controller\person::class);
$router->get("/(.*)", Controller\_404::class);

Application::$instance->useRouter($router);
Application::$instance->run();

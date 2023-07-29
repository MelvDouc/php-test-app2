<?php

use Melv\Test\Router;
use Melv\Test\Application;
use Melv\Test\Controller\HomeController;
use Melv\Test\Service\MySqlDatabaseService;
use Melv\Test\Service\TwigTemplateService;

$ROOT_DIR = dirname(__DIR__);

/** @var \Composer\Autoload\ClassLoader */
$autoLoader = require $ROOT_DIR . "/vendor/autoload.php";
$autoLoader->addPsr4("Melv\\Test\\Controller\\", $ROOT_DIR . "/controllers");
$autoLoader->addPsr4("Melv\\Test\\Model\\", $ROOT_DIR . "/models");
$autoLoader->addPsr4("Melv\\Test\\Service\\", $ROOT_DIR . "/services");

$app = new Application($ROOT_DIR);
$app->loadEnv();
$app->setTemplateEngine(TwigTemplateService::class);
$app->setDatabase(
  new MySqlDatabaseService($_ENV["DB_DSN"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"])
);

$router = new Router();
$homeController = new HomeController();

$router->get("/", [$homeController, "home"]);
$router->get("/about", [$homeController, "about"]);
$router->get("/profile/:id", [$homeController, "person"]);
$router->get("/(.*)", [$homeController, "_404"]);

$app->useRouter($router);
$app->run();

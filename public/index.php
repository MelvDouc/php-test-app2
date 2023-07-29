<?php

use Melv\Test\Router;
use Melv\Test\Application;
use Melv\Test\Controller\ApiController;
use Melv\Test\Controller\HomeController;
use Melv\Test\Service\MySqlDatabaseService;
use Melv\Test\Service\TwigTemplateService;

define("ROOT_DIR", dirname(__DIR__));

/** @var \Composer\Autoload\ClassLoader */
$autoLoader = require ROOT_DIR . "/vendor/autoload.php";
$autoLoader->addPsr4("Melv\\Test\\Controller\\", ROOT_DIR . "/controllers");
$autoLoader->addPsr4("Melv\\Test\\Model\\", ROOT_DIR . "/models");
$autoLoader->addPsr4("Melv\\Test\\Service\\", ROOT_DIR . "/services");

$app = new Application(ROOT_DIR);
$app->loadEnv();
$app->setTemplateEngine(TwigTemplateService::class);
$app->setDatabase(
  new MySqlDatabaseService($_ENV["DB_DSN"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"])
);

$apiRouter = new Router("/api/v1");
$apiController = new ApiController();
$apiRouter->get("/profile/:id", [$apiController, "person"]);

$clientRouter = new Router();
$homeController = new HomeController();
$clientRouter->get("/(home)?", [$homeController, "home"]);
$clientRouter->get("/about", [$homeController, "about"]);
$clientRouter->get("/.+", [$homeController, "_404"]);

$app->useRouters($apiRouter, $clientRouter);
$app->run();

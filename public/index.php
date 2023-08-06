<?php

use Melv\Test\Router;
use Melv\Test\Application;
use Melv\Test\Controller\ApiController;
use Melv\Test\Controller\HomeController;
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

$apiRouter = new Router("/api/v1");
$apiController = new ApiController();
$apiRouter->get("/profile/:id", [$apiController, "person"]);

$homeController = new HomeController();
$clientRouter = (new Router())
  ->get("/(home)?", [$homeController, "home_GET"])
  ->post("/(home)?", [$homeController, "home_POST"])
  ->get("/people", [$homeController, "people"])
  ->get("/about", [$homeController, "about"])
  ->get("/.+", [$homeController, "_404"]);

$app->useRouters($apiRouter, $clientRouter);
$app->run();

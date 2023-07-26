<?php

namespace Melv\Test;

use Melv\Test\Exception\PageNotFoundException;

class Application
{
  public static Application $instance;

  public readonly string $rootDir;
  private readonly Router $router;
  private Database $database;

  public function __construct(string $rootDir, Router $router)
  {
    static::$instance = $this;
    $this->rootDir = $rootDir;
    $this->router = $router;
  }

  public function getDatabase(): Database
  {
    return $this->database;
  }

  public function setDatabase(Database $database): Application
  {
    $this->database = $database;
    return $this;
  }

  public function run(): void
  {
    try {
      $method = $_SERVER["REQUEST_METHOD"];
      $url = $_SERVER["REQUEST_URI"];
      $handler = $this->router->findHandler($method, $url);

      if (!$handler)
        throw new PageNotFoundException();

      call_user_func_array($handler[0], [
        new Request($method, $url, $_GET, $handler[1], $this->getBody($method)),
        new Response()
      ]);
    } catch (\Exception $e) {
      if ($e instanceof PageNotFoundException) {
        echo $e->getMessage();
        exit;
      }
    }
  }

  protected function getBody(string $method)
  {
    return match ($method) {
      "POST" => $_POST,
      "PATCH", "PUT" => json_decode(file_get_contents("php://input")),
      default => null,
    };
  }
}

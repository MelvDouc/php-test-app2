<?php

namespace Melv\Test;

use Melv\Test\Exception\PageNotFoundException;

class Application
{
  public static Application $instance;

  public readonly string $rootDir;
  private $routers = [];
  private Database $database;

  public function __construct(string $rootDir)
  {
    static::$instance = $this;
    $this->rootDir = $rootDir;
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

  public function useRouter(Router $router): Application
  {
    $this->routers[] = $router;
    return $this;
  }

  public function run(): void
  {
    try {
      $method = $_SERVER["REQUEST_METHOD"];
      $url = $_SERVER["REQUEST_URI"];

      foreach ($this->routers as $router) {
        $handler = $router->findHandler($method, $url);

        if ($handler) {
          call_user_func_array($handler[0], [
            new Request($method, $url, $_GET, $handler[1], $this->getBody($method)),
            new Response()
          ]);
          return;
        }
      }

      throw new PageNotFoundException();
    } catch (\Exception $e) {
      if ($e instanceof PageNotFoundException) {
        echo $e->getMessage();
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

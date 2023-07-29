<?php

namespace Melv\Test;

use Dotenv\Dotenv;
use Melv\Test\Exception\PageNotFoundException;
use Melv\Test\Service\Interface\DatabaseService;

class Application
{
  public static Application $instance;

  public static function create(string $ROOT_DIR): static
  {
    return new static($ROOT_DIR);
  }

  public readonly string $ROOT_DIR;
  /** @var Router[] $routers */
  protected $routers = [];
  protected DatabaseService $database;

  public function __construct(string $ROOT_DIR)
  {
    static::$instance = $this;
    $this->ROOT_DIR = $ROOT_DIR;
  }

  public function getDatabase(): mixed
  {
    return $this->database;
  }

  public function setDatabase(DatabaseService $database): Application
  {
    $this->database = $database;
    return $this;
  }

  public function getPhpEnv(): ?string
  {
    return $_ENV["PHP_ENV"] ?? null;
  }

  public function setTemplateEngine(string $templateEngine): Application
  {
    Response::setTemplateServiceClassName($templateEngine);
    return $this;
  }

  public function loadEnv(): void
  {
    try {
      if ($this->getPhpEnv() === null)
        Dotenv::createImmutable(ROOT_DIR)->load();
    } catch (\Throwable $e) {
      $this->handleError($e);
    }
  }

  public function useRouters(Router $router, Router ...$routers): Application
  {
    array_push($this->routers, $router, ...$routers);
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
          call_user_func_array($handler["fn"], [
            new Request($this, $method, $url, $_GET, $handler["params"] ?? null, $this->getBody($method)),
            new Response()
          ]);
          return;
        }
      }

      throw new PageNotFoundException();
    } catch (\Exception $e) {
      $this->handleError($e);
    }
  }

  public function handleError(\Throwable $e, string $color = "red")
  {
    echo "<pre style=\"color: $color; font-family: 'Fira Code', 'Ubuntu Mono', Consolas, 'Courier New', monospace;\">";
    var_dump($e->getMessage());
    echo "</pre>";
    exit;
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

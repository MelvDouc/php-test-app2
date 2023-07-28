<?php

namespace Melv\Test;

use Dotenv\Dotenv;
use Melv\Test\Exception\PageNotFoundException;
use Melv\Test\Service\Interface\DatabaseService;

class Application
{
  public static Application $instance;

  public static function create(string $rootDir): static
  {
    return new static($rootDir);
  }

  public readonly string $rootDir;
  private $routers = [];
  private DatabaseService $database;

  public function __construct(string $rootDir)
  {
    static::$instance = $this;
    $this->rootDir = $rootDir;
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

  public function loadEnv(): void
  {
    try {
      if ($this->getPhpEnv() === null)
        Dotenv::createImmutable($this->rootDir)->load();
    } catch (\Throwable $e) {
      $this->handleError($e);
    }
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
      Application::$instance->handleError($e);
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

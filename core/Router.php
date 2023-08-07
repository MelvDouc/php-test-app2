<?php

namespace Melv\Test;

class Router
{
  /** @var list<string, list<string, callable[]>> */
  protected array $routes = [
    "GET" => [],
    "POST" => [],
    "PUT" => [],
    "PATCH" => [],
    "DELETE" => []
  ];
  protected readonly string $prefix;

  /**
   * @param string $prefix Must not end with a slash.
   */
  public function __construct(string $prefix = "")
  {
    $this->prefix = $prefix;
  }

  public function getRoutes(): array
  {
    return $this->routes;
  }

  /**
   * @param string $path
   * @param callable(Request $req, Response $res, ?callable $next): mixed $handler
   */
  public function get(string $path, callable $handler, callable ...$handlers): Router
  {
    return $this->addHandlers("GET", $path, $handler, ...$handlers);
  }

  public function post(string $path, callable $handler, callable ...$handlers): Router
  {
    return $this->addHandlers("POST", $path, $handler, ...$handlers);
  }

  public function put(string $path, callable $handler, callable ...$handlers): Router
  {
    return $this->addHandlers("PUT", $path, $handler, ...$handlers);
  }

  public function patch(string $path, callable $handler, callable ...$handlers): Router
  {
    return $this->addHandlers("PATCH", $path, $handler, ...$handlers);
  }

  public function delete(string $path, callable $handler, callable ...$handlers): Router
  {
    return $this->addHandlers("DELETE", $path, $handler, ...$handlers);
  }

  protected function addHandlers(string $method, string $path, callable $handler, callable ...$handlers): Router
  {
    $key = preg_replace(
      "/:(\w+)/",
      "(?P<$1>\\w+)",
      preg_replace("/\//", "\\/", $this->prefix . $path)
    );
    $this->routes[$method]["/^$key$/"] = [$handler, ...$handlers];
    return $this;
  }
}

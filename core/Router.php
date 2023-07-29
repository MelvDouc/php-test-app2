<?php

namespace Melv\Test;

class Router
{
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

  /**
   * @param string $path
   * @param callable(Request $req, Response $res): mixed $handler
   */
  public function get(string $path, callable $handler): Router
  {
    return $this->registerHandler("GET", $path, $handler);
  }

  public function post(string $path, callable $handler): Router
  {
    return $this->registerHandler("POST", $path, $handler);
  }

  public function put(string $path, callable $handler): Router
  {
    return $this->registerHandler("PUT", $path, $handler);
  }

  public function patch(string $path, callable $handler): Router
  {
    return $this->registerHandler("PATCH", $path, $handler);
  }

  public function delete(string $path, callable $handler): Router
  {
    return $this->registerHandler("DELETE", $path, $handler);
  }

  public function findHandler(string $method, string $path): ?array
  {
    foreach ($this->routes[$method] as $key => $value) {
      if ($key === $path)
        return ["fn" => $value];

      if (preg_match($key, $path, $params))
        return [
          "fn"     => $value,
          "params" => $params
        ];
    }

    return null;
  }

  private function registerHandler(string $method, string $path, callable $handler): Router
  {
    $key = preg_replace(
      "/:(\w+)/",
      "(?P<$1>\\w+)",
      preg_replace("/\//", "\\/", $this->prefix . $path)
    );
    $this->routes[$method]["/^$key$/"] = $handler;
    return $this;
  }
}

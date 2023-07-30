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

  public function findHandlers(string $method, string $path): ?array
  {
    if (isset($this->routes[$method][$path]))
      return [
        "handlers" => $this->routes[$method][$path],
        "params"   => null
      ];

    foreach ($this->routes[$method] as $key => $handlers) {
      if (preg_match($key, $path, $params))
        return [
          "handlers" => $handlers,
          "params"   => $params
        ];
    }

    return null;
  }

  public function getRecursiveHandler(array $handlers, Request $req, Response $res): \Closure
  {
    $handler = null;

    for ($i = count($handlers) - 1; $i >= 0; $i--) {
      $prevFn = $handler;
      $handler = function () use ($handlers, $prevFn, $i, $req, $res) {
        call_user_func_array($handlers[$i], [$req, $res, $prevFn]);
      };
    }

    return $handler;
  }

  private function addHandlers(string $method, string $path, callable $handler, callable ...$handlers): Router
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

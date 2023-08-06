<?php

namespace Melv\Test;

class Request
{
  public readonly string $method;
  public readonly string $url;
  public readonly array $queryParams;
  public readonly array $urlParams;
  public readonly Application $app;
  public readonly ?array $body;

  public function __construct(
    Application $app,
    string $method,
    string $url,
    array $queryParams,
    array $urlParams,
    ?array $body = null
  ) {
    $this->app = $app;
    $this->method = $method;
    $this->url = $url;
    $this->queryParams = $queryParams;
    $this->urlParams = $urlParams;
    $this->body = $body;
  }
}

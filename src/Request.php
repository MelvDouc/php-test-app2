<?php

namespace Melv\Test;

class Request
{
  public readonly string $method;
  public readonly string $url;
  public readonly array $queryParams;
  public readonly array $urlParams;
  public readonly ?array $body;

  public function __construct(string $method, string $url, array $queryParams, array $urlParams, ?array $body = null)
  {
    $this->method = $method;
    $this->url = $url;
    $this->queryParams = $queryParams;
    $this->urlParams = $urlParams;
    $this->body = $body;
  }
}

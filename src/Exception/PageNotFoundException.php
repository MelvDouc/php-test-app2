<?php

namespace Melv\Test\Exception;

class PageNotFoundException extends \Exception
{
  public function __construct(string $message = "")
  {
    $this->message = $message ? $message : "<h1>404 - Page Not Found</h1>";
  }
}

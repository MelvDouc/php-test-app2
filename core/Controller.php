<?php

namespace Melv\Test;

abstract class Controller
{
  protected static self $instance;

  public static function getInstance(): static
  {
    self::$instance ??= new static();
    return self::$instance;
  }
}

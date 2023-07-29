<?php

namespace Melv\Test;

use Melv\Test\Service\Interface\TemplateService;

class Response
{
  protected static string $templateServiceClassName;
  protected static TemplateService $templateService;

  public static function setTemplateServiceClassName($name)
  {
    static::$templateServiceClassName = $name;
  }

  protected int $statusCode = 200;

  public function __construct()
  {
    self::$templateService ??= new (static::$templateServiceClassName)();
  }

  /**
   * Defaults to 200.
   */
  public function getStatusCode(): int
  {
    return $this->statusCode;
  }

  public function setStatusCode(int $statusCode): Response
  {
    $this->statusCode = $statusCode;
    return $this;
  }

  /**
   * @param mixed $data Must be serializable.
   */
  public function json(mixed $data): void
  {
    http_response_code($this->statusCode);
    header("Content-Type: application/json");
    echo json_encode($data);
  }

  public function redirect(string $url): void
  {
    header("Location: $url");
  }

  public function render(string $template, array $context = []): void
  {
    http_response_code($this->statusCode);
    echo self::$templateService->render($template, $context);
  }

  public function write(string $message): void
  {
    http_response_code($this->statusCode);
    echo $message;
  }
}

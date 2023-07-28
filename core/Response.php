<?php

namespace Melv\Test;

use Melv\Test\Service\Interface\TemplateService;
use Melv\Test\Service\TwigTemplateService;

class Response
{
  protected static TemplateService $templateService;

  protected int $statusCode = 200;

  public function __construct()
  {
    self::$templateService ??= new TwigTemplateService();
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

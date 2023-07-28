<?php

namespace Melv\Test;

use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader as TwigFileSystemLoader;
use Twig\TwigFunction as TwigFunction;

class Response
{
  protected static TwigEnvironment $twig;

  protected int $statusCode = 200;

  public function __construct()
  {
    if (!isset(self::$twig)) {
      $templatesDir = Application::$instance->rootDir . "/templates";
      $loader = new TwigFileSystemLoader($templatesDir);
      $options = [];
      if (Application::$instance->getPhpEnv() === "production")
        $options["cache"] = "$templatesDir/.cache";
      self::$twig = new TwigEnvironment($loader, $options);
      self::$twig->addFunction(
        new TwigFunction("assets", fn ($arg) => "/assets/$arg")
      );
    }
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
    echo self::$twig->render($template, $context);
  }

  public function write(string $message): void
  {
    http_response_code($this->statusCode);
    echo $message;
  }
}

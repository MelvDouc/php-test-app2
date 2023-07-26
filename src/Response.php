<?php

namespace Melv\Test;

class Response
{
  private readonly \Twig\Environment $twig;
  private int $statusCode = 200;

  public function __construct()
  {
    $templatesDir = Application::$instance->rootDir . "/templates";
    $loader = new \Twig\Loader\FilesystemLoader($templatesDir);
    $assetsFn = new \Twig\TwigFunction("assets", fn ($arg) => "/assets/$arg");

    $this->twig = new \Twig\Environment($loader, [
      "cache" => $templatesDir . "/.cache"
    ]);
    $this->twig->addFunction($assetsFn);
  }

  public function getStatusCode(): int
  {
    return $this->statusCode;
  }

  public function setStatusCode(int $statusCode): Response
  {
    $this->statusCode = $statusCode;
    return $this;
  }

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
    echo $this->twig->render($template, $context);
  }

  public function write(string $message): void
  {
    http_response_code($this->statusCode);
    echo $message;
  }
}

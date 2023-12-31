<?php

namespace Melv\Test\Service;

use Melv\Test\Service\Interface\TemplateService;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader as TwigFileSystemLoader;
use Twig\TwigFunction as TwigFunction;

class TwigTemplateService implements TemplateService
{
  protected static TwigEnvironment $twig;

  public function __construct()
  {
    if (!isset(self::$twig)) {
      $templatesDir = ROOT_DIR . "/templates";
      $loader = new TwigFileSystemLoader($templatesDir);
      self::$twig = new TwigEnvironment($loader);
      self::$twig->addFunction(
        new TwigFunction("assets", fn ($arg) => "/assets/$arg")
      );
    }
  }

  public function render(string $template, array $context = []): string
  {
    return self::$twig->render($template, $context);
  }
}

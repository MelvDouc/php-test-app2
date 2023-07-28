<?php

namespace Melv\Test\Service\Interface;

interface TemplateService
{
  public function render(string $template, array $context): string;
}

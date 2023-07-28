<?php

namespace Melv\Test\Service;

interface TemplateService
{
  public function render(string $template, array $context): string;
}

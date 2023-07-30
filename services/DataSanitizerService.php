<?php

namespace Melv\Test\Service;

class DataSanitizerService
{
  public static function sanitizeString(string $str): string
  {
    return htmlspecialchars(trim($str), ENT_QUOTES, "UTF-8");
  }

  public static function sanitizeArray(array $arr): array
  {
    $result = [];

    foreach ($arr as $key => $value) {
      $result[$key] = static::sanitizeString($value);
    }

    return $result;
  }

  public static function sanitizeToInt(string $str): int
  {
    return filter_var($str, FILTER_VALIDATE_INT);
  }

  public static function isValidEmail(string $email): bool
  {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
  }
}

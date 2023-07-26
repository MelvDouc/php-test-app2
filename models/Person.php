<?php

namespace Melv\Test\Model;

class Person
{
  public static function map(array $entity): Person
  {
    $person = new self();
    $person->firstName = $entity["firstName"];
    $person->lastName = $entity["lastName"];
    $person->address = $entity["address"];
    $person->city = [
      "name" => $entity["city_name"],
      "zipCode" => $entity["city_zipCode"],
      "country" => $entity["city_country"]
    ];
    $person->isMale = (bool) $entity["isMale"];
    return $person;
  }

  public string $firstName;
  public string $lastName;
  public string $address;
  public array $city;
  public bool $isMale;
}

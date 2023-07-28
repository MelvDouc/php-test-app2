<?php

namespace Melv\Test\Model;

class Person
{
  public static function map(array $p, array $city): Person
  {
    $person = new self();
    $person->id = $p["id"];
    $person->firstName = $p["firstName"];
    $person->lastName = $p["lastName"];
    $person->street = $p["street"];
    $person->city = (object) $city;
    $person->isMale = $p["gender"] === "M";
    return $person;
  }

  public int $id;
  public string $firstName;
  public string $lastName;
  public string $street;
  public object $city;
  public bool $isMale;
}

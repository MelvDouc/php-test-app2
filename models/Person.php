<?php

namespace Melv\Test\Model;

use Melv\Test\Application;
use Melv\Test\Model;

class Person implements Model
{
  public static function getById(int $id): ?static
  {
    $statement = Application::$instance
      ->getDatabase()
      ->prepare("SELECT * FROM person WHERE id = :id");

    if (!$statement->execute(["id" => $id]) || !($data = $statement->fetch()))
      return null;

    $person = new static();
    $city = City::getById($data["cityId"]);
    return $person
      ->setId($data["id"])
      ->setFirstName($data["firstName"])
      ->setLastName($data["lastName"])
      ->setStreet($data["street"])
      ->setCity($city)
      ->setIsMale($data["gender"] === "M");
  }

  public static function getAll(): array
  {
    $personsInDb = Application::$instance
      ->getDatabase()
      ->query("SELECT * FROM person")
      ->fetchAll();
    $cities = City::getAll();
    return array_map(
      fn ($p) => (new Person())
        ->setId($p["id"])
        ->setFirstName($p["firstName"])
        ->setLastName($p["lastName"])
        ->setStreet($p["street"])
        ->setCity($cities[$p["cityId"] - 1])
        ->setIsMale($p["gender"] === "M"),
      $personsInDb
    );
  }

  private int $id;
  private string $firstName;
  private string $lastName;
  private string $street;
  private City $city;
  private bool $isMale;

  public function getId(): int
  {
    return $this->id;
  }

  public function setId(int $id): Person
  {
    $this->id = $id;
    return $this;
  }

  public function getFirstName(): string
  {
    return $this->firstName;
  }

  public function setFirstName(string $firstName): Person
  {
    $this->firstName = $firstName;
    return $this;
  }

  public function getLastName(): string
  {
    return $this->lastName;
  }

  public function setLastName(string $lastName): Person
  {
    $this->lastName = $lastName;
    return $this;
  }

  public function getStreet(): string
  {
    return $this->street;
  }

  public function setStreet(string $street): Person
  {
    $this->street = $street;
    return $this;
  }

  public function getCity(): City
  {
    return $this->city;
  }

  public function setCity(City $city): Person
  {
    $this->city = $city;
    return $this;
  }

  public function getIsMale(): bool
  {
    return $this->isMale;
  }

  public function setIsMale(bool $isMale): Person
  {
    $this->isMale = $isMale;
    return $this;
  }

  public function save(): void
  {
    $statement = Application::$instance
      ->getDatabase()
      ->prepare("INSERT INTO person (firstName, lastName, street, cityId, gender)
      VALUES (:firstName, :lastName, :street, :cityId, :gender)");
    $statement->execute([
      "firstName" => $this->firstName,
      "lastName"  => $this->lastName,
      "street"    => $this->street,
      "cityId"    => $this->city->getId(),
      "gender"    => $this->isMale ? "M" : "F"
    ]);
  }

  public function update()
  {
    $statement = Application::$instance
      ->getDatabase()
      ->prepare("UPDATE person
        SET firstName = :firstName,
          lastName = :lastName,
          street = :street,
          cityId = :cityId,
          gender = :gender
        WHERE id = :id");
    $statement->execute([
      "firstName" => $this->firstName,
      "lastName"  => $this->lastName,
      "street"    => $this->street,
      "cityId"    => $this->city->getId(),
      "gender"    => $this->isMale ? "M" : "F"
    ]);
  }

  public function toJson(): array
  {
    return [
      "firstName" => $this->firstName,
      "lastName"  => $this->lastName,
      "street"    => $this->street,
      "city"      => $this->city->toJson(),
      "isMale"    => $this->isMale
    ];
  }
}

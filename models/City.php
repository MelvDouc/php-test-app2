<?php

namespace Melv\Test\Model;

use Melv\Test\Application;
use Melv\Test\Model;

final class City implements Model
{
  public static function getById(int $id): ?static
  {
    $statement = Application::$instance
      ->getDatabase()
      ->prepare("SELECT * FROM city WHERE id = :id");

    if (!$statement->execute(["id" => $id]) || !($data = $statement->fetch()))
      return null;

    return (new static())
      ->setId($data["id"])
      ->setName($data["name"])
      ->setZipCode($data["zipCode"])
      ->setCountry($data["country"]);
  }

  public static function getAll()
  {
    $query = Application::$instance
      ->getDatabase()
      ->query("SELECT * FROM city ORDER BY id")
      ->fetchAll();

    return array_map(
      function ($c) {
        return (new static())
          ->setId($c["id"])
          ->setName($c["name"])
          ->setZipCode($c["zipCode"])
          ->setCountry($c["country"]);
      },
      $query
    );
  }

  private int $id;
  private string $name;
  private string $zipCode;
  private string $country;

  public function getId(): int
  {
    return $this->id;
  }

  public function setId(int $id): City
  {
    $this->id = $id;
    return $this;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): City
  {
    $this->name = $name;
    return $this;
  }

  public function getZipCode(): string
  {
    return $this->zipCode;
  }

  public function setZipCode(string $zipCode): City
  {
    $this->zipCode = $zipCode;
    return $this;
  }

  public function getCountry(): string
  {
    return $this->country;
  }

  public function setCountry(string $country): City
  {
    $this->country = $country;
    return $this;
  }

  public function save(): void
  {
    $statement = Application::$instance
      ->getDatabase()
      ->prepare("INSERT INTO city (name, zipCode, country) VALUES (:name, :zipCode, :country)");
    $statement->execute([
      "name" => $this->name,
      "zipCode" => $this->zipCode,
      "country" => $this->country
    ]);
  }

  public function update()
  {
  }

  public function toJson()
  {
    return [
      "id" => $this->id,
      "name" => $this->name,
      "zipCode" => $this->zipCode,
      "country" => $this->country
    ];
  }
}

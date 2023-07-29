<?php

namespace Melv\Test\Controller;

use Melv\Test\Controller;
use Melv\Test\Model\Person;
use Melv\Test\Request;
use Melv\Test\Response;

class ApiController extends Controller
{
  public function person(Request $req, Response $res): void
  {
    $personStatement = $req->app
      ->getDatabase()
      ->prepare("SELECT * FROM person WHERE id = :id");

    if (
      !$personStatement->execute(["id" => (int) $req->urlParams["id"]])
      || !($person = $personStatement->fetch())
    ) {
      $res->setStatusCode(404)->json(null);
      return;
    }

    $cityStatement = $req->app
      ->getDatabase()
      ->prepare("SELECT * FROM city WHERE id = :id LIMIT 1");
    $cityStatement->execute(["id" => $person["cityId"]]);
    $city = $cityStatement->fetch(\PDO::FETCH_ASSOC);

    $res->json(Person::map($person, $city));
  }
}

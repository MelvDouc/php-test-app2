<?php

namespace Melv\Test\Controller;

use Melv\Test\Controller;
use Melv\Test\Request;
use Melv\Test\Response;
use Melv\Test\Model\Person;

class HomeController extends Controller
{
  public function home_GET(Request $req, Response $res)
  {
    $res->render("home.twig");
  }

  public function home_POST(Request $req, Response $res)
  {
    $person = $req->body;
    $statement = $req->app
      ->getDatabase()
      ->prepare("INSERT INTO person (firstName, lastName, street, cityId, gender)
        VALUES (:firstName, :lastName, :street, :cityId, :gender)");
    $statement->execute([
      "firstName" => $person["firstName"],
      "lastName"  => $person["lastName"],
      "street"    => $person["street"],
      "cityId"    => (int) $person["cityId"],
      "gender"    => isset($person["isMale"]) ? "M" : "F"
    ]);
    $res->redirect("/people");
  }

  public function people(Request $req, Response $res): void
  {
    $personStatement = $req->app
      ->getDatabase()
      ->query("SELECT * FROM person");
    $cityStatement = $req->app
      ->getDatabase()
      ->query("SELECT * FROM city ORDER BY id");

    if (!$personStatement || !$cityStatement) {
      $res->setStatusCode(500)->write("<h1>An error occurred.</h1>");
      return;
    }

    $persons = $personStatement->fetchAll();
    $cities = $cityStatement->fetchAll();

    $res->render("people.twig", [
      "persons" => array_map(
        fn ($p) => Person::map($p, $cities[$p["cityId"] - 1]),
        $persons
      )
    ]);
  }

  public function about(Request $req, Response $res): void
  {
    $res->render("about.twig");
  }

  public function _404(Request $req, Response $res): void
  {
    $res->setStatusCode(404)->render("404.twig");
  }
}

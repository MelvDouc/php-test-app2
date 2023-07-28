<?php

namespace Melv\Test\Controller;

use Melv\Test\Application;
use Melv\Test\Controller;
use Melv\Test\Request;
use Melv\Test\Response;
use Melv\Test\Model\Person;
use PDO;

class HomeController extends Controller
{
  public function home(Request $req, Response $res)
  {
    $personStatement = Application::$instance
      ->getDatabase()
      ->connection
      ->query("SELECT * FROM person");
    $cityStatement = Application::$instance
      ->getDatabase()
      ->connection
      ->query("SELECT * FROM city ORDER BY id");

    if (!$personStatement || !$cityStatement) {
      $res->setStatusCode(500)->write("<h1>An error occurred.</h1>");
      return;
    }

    $persons = $personStatement->fetchAll();
    $cities = $cityStatement->fetchAll();

    $res->render("home.twig", [
      "persons" => array_map(
        fn ($p) => Person::map($p, $cities[$p["cityId"] - 1]),
        $persons
      )
    ]);
  }

  public function about(Request $req, Response $res)
  {
    $res->render("about.twig");
  }

  public function person(Request $req, Response $res): void
  {
    $personStatement = Application::$instance
      ->getDatabase()
      ->connection
      ->prepare("SELECT * FROM person WHERE id = :id");

    if (
      !$personStatement->execute(["id" => (int) $req->urlParams["id"]])
      || !($person = $personStatement->fetch())
    ) {
      $res->setStatusCode(404)->json(null);
      return;
    }

    $cityStatement = Application::$instance
      ->getDatabase()
      ->connection
      ->prepare("SELECT * FROM city WHERE id = :id LIMIT 1");
    $cityStatement->execute(["id" => $person["cityId"]]);
    $city = $cityStatement->fetch(PDO::FETCH_ASSOC);

    $res->json(Person::map($person, $city));
  }

  public function _404(Request $req, Response $res)
  {
    $res->setStatusCode(404)->render("404.twig");
  }
}

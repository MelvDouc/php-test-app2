<?php

namespace Melv\Test\Controller;

use Melv\Test\Controller;
use Melv\Test\Request;
use Melv\Test\Response;
use Melv\Test\Model\Person;
use PDO;

class HomeController extends Controller
{
  public function home(Request $req, Response $res)
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

  public function _404(Request $req, Response $res)
  {
    $res->setStatusCode(404)->render("404.twig");
  }
}

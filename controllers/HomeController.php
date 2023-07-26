<?php

namespace Melv\Test\Controller;

use Melv\Test\Application;
use Melv\Test\Request;
use Melv\Test\Response;

require_once dirname(__DIR__) . "/models/Person.php";

function home(Request $req, Response $res)
{
  $persons = Application::$instance
    ->getDatabase()
    ->connection
    ->query("SELECT * FROM person");
  $res->render("home.twig", ["persons" => $persons]);
}

function about(Request $req, Response $res)
{
  $res->render("about.twig");
}

function person(Request $req, Response $res): void
{
  $id = (int) $req->urlParams["id"];
  $statement = Application::$instance
    ->getDatabase()
    ->connection
    ->prepare("
      SELECT
        firstName,
        lastName,
        address,
        c.name city_name,
        c.zipCode city_zipCode,
        c.country city_country,
        isMale
      FROM person p
        JOIN city c ON c.id = p.cityId
      WHERE p.id = :id
      LIMIT 1
    ");

  if (!$statement->execute(["id" => $id])) {
    $res->setStatusCode(404)->json(null);
    return;
  }

  $person = $statement->fetch();
  $res->json(\Melv\Test\Model\Person::map($person));
}

function _404(Request $req, Response $res)
{
  $res->setStatusCode(404)->render("404.twig");
}

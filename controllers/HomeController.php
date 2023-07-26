<?php

namespace Melv\Test\Controller;

use Melv\Test\Request;
use Melv\Test\Response;
use Melv\Test\Application;
use Melv\Test\Model\Person;

$selectPersonSql = file_get_contents(dirname(__DIR__) . "/models/sql/select-person.sql");

function home(Request $req, Response $res)
{
  global $selectPersonSql;
  $persons = Application::$instance
    ->getDatabase()
    ->connection
    ->query("$selectPersonSql ORDER BY id");
  $persons = array_map(fn ($p) => Person::map($p), $persons->fetchAll());
  $res->render("home.twig", [
    "persons" => $persons
  ]);
}

function about(Request $req, Response $res)
{
  $res->render("about.twig");
}

function person(Request $req, Response $res): void
{
  global $selectPersonSql;
  $statement = Application::$instance
    ->getDatabase()
    ->connection
    ->prepare("$selectPersonSql WHERE p.id = :id");

  if (
    !$statement->execute(["id" => (int) $req->urlParams["id"]])
    || !($person = $statement->fetch())
  ) {
    $res->setStatusCode(404)->json(null);
    return;
  }

  $res->json(Person::map($person));
}

function _404(Request $req, Response $res)
{
  $res->setStatusCode(404)->render("404.twig");
}

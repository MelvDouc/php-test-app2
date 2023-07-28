<?php

namespace Melv\Test\Controller;

use Melv\Test\Application;
use Melv\Test\Controller;
use Melv\Test\Request;
use Melv\Test\Response;
use Melv\Test\Model\Person;

class HomeController extends Controller
{
  private string $_selectPersonSql;

  private function getSelectPersonSql(): string
  {
    $this->_selectPersonSql ??= file_get_contents(Application::$instance->rootDir . "/models/sql/select-person.sql");
    return $this->_selectPersonSql;
  }

  public function home(Request $req, Response $res)
  {
    $persons = Application::$instance
      ->getDatabase()
      ->connection
      ->query($this->getSelectPersonSql() . " ORDER BY id");

    if (!$persons) {
      $res->setStatusCode(500)->write("<h1>An error occurred.</h1>");
      return;
    }

    $res->render("home.twig", [
      "persons" => array_map("Melv\\Test\\Model\\Person::map", $persons->fetchAll())
    ]);
  }

  public function about(Request $req, Response $res)
  {
    $res->render("about.twig");
  }

  public function person(Request $req, Response $res): void
  {
    $statement = Application::$instance
      ->getDatabase()
      ->connection
      ->prepare($this->getSelectPersonSql() . " WHERE p.id = :id");

    if (
      !$statement->execute(["id" => (int) $req->urlParams["id"]])
      || !($person = $statement->fetch())
    ) {
      $res->setStatusCode(404)->json(null);
      return;
    }

    $res->json(Person::map($person));
  }

  public function _404(Request $req, Response $res)
  {
    $res->setStatusCode(404)->render("404.twig");
  }
}

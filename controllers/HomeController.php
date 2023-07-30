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
    $person = new Person();
    $person
      ->setFirstName($req->body["firstName"])
      ->setLastName($req->body["lastName"])
      ->setStreet($req->body["street"])
      ->setCity((object) ["id" => (int) $req->body["cityId"]])
      ->setIsMale(!is_null($req->body["isMale"]));
    $person->save();
    $res->redirect("/people");
  }

  public function home_PATCH(Request $req, Response $res): void
  {
    $person = Person::getById((int) $req->queryParams["id"]);

    if (isset($req->body["firstName"]))
      $person->setFirstName($req->body["firstName"]);
    if (isset($req->body["lastName"]))
      $person->setLastName($req->body["lastName"]);
    if (isset($req->body["street"]))
      $person->setStreet($req->body["street"]);
    if (isset($req->body["cityId"]))
      $person->getCity()->setId((int) $req->body["cityId"]);
    if (isset($req->body["isMale"]))
      $person->setIsMale($req->body["isMale"]);

    $person->update();
    $res->redirect("/people");
  }

  public function people(Request $req, Response $res): void
  {
    $persons = Person::getAll();
    $res->render("people.twig", [
      "persons" => $persons
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

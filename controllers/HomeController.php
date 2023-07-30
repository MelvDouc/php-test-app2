<?php

namespace Melv\Test\Controller;

use Melv\Test\Controller;
use Melv\Test\Model\City;
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

  public function people(Request $req, Response $res): void
  {
    $persons = Person::getAll();
    $cities = City::getAll();

    foreach ($persons as $person)
      $person->setCity($cities[$person->getCity()->getId() - 1]);

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

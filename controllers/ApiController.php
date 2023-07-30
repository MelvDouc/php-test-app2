<?php

namespace Melv\Test\Controller;

use Melv\Test\Controller;
use Melv\Test\Model\City;
use Melv\Test\Model\Person;
use Melv\Test\Request;
use Melv\Test\Response;

class ApiController extends Controller
{
  public function person(Request $req, Response $res): void
  {
    $person = Person::getById((int) $req->urlParams["id"]);

    if ($person) {
      $city = City::getById($person->getCity()->getId());
      $city && $person->setCity($city);
    }

    $res->json($person->toJson());
  }
}

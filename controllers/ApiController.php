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
    $id = (int) $req->urlParams["id"];
    $res->json(Person::getById($id)?->toJson());
  }
}

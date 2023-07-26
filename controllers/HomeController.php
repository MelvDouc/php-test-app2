<?php

namespace Melv\Test\Controller;

use Melv\Test\Application;
use Melv\Test\Request;
use Melv\Test\Response;

function home(Request $req, Response $res)
{
  $users = Application::$instance
    ->getDatabase()
    ->connection
    ->query("SELECT * FROM user ORDER BY email");
  $res->render("home.twig", ["users" => $users]);
}

function about(Request $req, Response $res)
{
  $res->render("about.twig");
}

function profile(Request $req, Response $res): void
{
  $query = Application::$instance
    ->getDatabase()
    ->connection
    ->prepare("SELECT * FROM user WHERE id = :id LIMIT 1");
  $query->execute([
    "id" => $req->urlParams["id"]
  ]);
  $user = $query->fetch(\PDO::FETCH_ASSOC);
  $res->json($user ? $user : null);
}

function _404(Request $req, Response $res)
{
  $res->setStatusCode(404)->render("404.twig");
}

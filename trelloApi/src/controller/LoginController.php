<?php
namespace controller;

use Slim\Http\Request;
use Slim\Http\Response;

class LoginController
{
    public function login(Request $request, Response $response)
    {
        $response = $response->withJson(["response" => "Hello"], 200);
        return $response;
    }
}
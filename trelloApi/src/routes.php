<?php

use Slim\Http\Request;
use Slim\Http\Response;
use controller\LoginController;

// Routes
$app->get('/login', \controller\LoginController::class . ':login');

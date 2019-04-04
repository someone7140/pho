<?php

use Slim\Http\Request;
use Slim\Http\Response;
use controller\LoginController;

// Routes
$app->get('/login', \controller\LoginController::class . ':login');
$app->get('/authorize', \controller\LoginController::class . ':authorize');
$app->get('/auth_callback', \controller\LoginController::class . ':auth_callback');

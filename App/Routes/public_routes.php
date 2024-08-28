<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$this->route->get('/', function() {
	include_once __DIR__ . '/../Views/pages/home.php';
});

$this->route->add('GET|POST', '/signup', function() {
	include_once __DIR__ . '/../Views/pages/signup.php';
});

$this->route->add('GET|POST', '/signin', function() {
    include_once __DIR__ . '/../Views/pages/signin.php';
});

$this->route->get('/dashboard', function() {
    include_once __DIR__ . '/../Views/pages/dashboard.php';
});

$this->route->error(function(Request $request, Response $response, Exception $exception) {
    echo $exception->getMessage();
});
<?php 

$this->route->get('/', function() {
	include_once __DIR__ . '/../Views/pages/home.php';
});

$this->route->add('GET|POST', '/signup', function() {
	include_once __DIR__ . '/../Views/pages/signup.php';
});

$this->route->add('GET|POST', '/signin', function() {
    include_once __DIR__ . '/../Views/pages/signin.php';
});

$this->route->error(function() {
 	echo "Ocurrió un problema con la ruta. Al parecer no existe.";
});
<?php 

$this->route->get('/', function() {
	include_once __DIR__.'/../views/pages/inicio.php';
});

$this->route->error(function() {
 	echo "Ocurrió un problema con la ruta. Al parecer no existe.";
});
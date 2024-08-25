<?php 

// Load composer
require __DIR__.'/vendor/autoload.php';


use App\controllers\usuarios;


if($_POST['accion'] && $_POST['accion']=="crearUsuario" ){
	$usuarios = new usuarios();
	$usuarios->crearr($_POST);
}

 
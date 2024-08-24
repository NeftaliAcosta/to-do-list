<?php 
//requiere la configuracion del sistema
require_once "config.php";

//requiere los controladores y librerias del sistema
require __DIR__.'/app/app_autoloader.php';

//requiere las librerias de composer
require __DIR__.'/vendor/autoload.php';


use App\controllers\usuarios;


if($_POST['accion'] && $_POST['accion']=="crearUsuario" ){
	$usuarios = new usuarios();
	$usuarios->crearr($_POST);
}

 
<?php 

date_default_timezone_set('America/Mexico_City');
define('__ver__', "1.0");
define('_prod_', false);


if(_prod_===true){
	define('DB_USER','');
	define('DB_NAME','');
	define('DB_PASS','');
	define('DB_HOST','');
	define('__PATH__', 'https://'.$_SERVER['SERVER_NAME'].'/');
	define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].'/');


}else{
	define('DB_USER','root');
	define('DB_NAME','crud');
	define('DB_PASS','');
	define('DB_HOST','localhost');
	define('__PATH__', 'http://'.$_SERVER['SERVER_NAME'].'/'); 
	define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].'/');

	// Show errors
	error_reporting(-1);
	error_reporting(0);
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL); 
}
<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('__PATH__', 'http://'.$_SERVER['SERVER_NAME']);

// Set default timezone
date_default_timezone_set('America/Mexico_City');

// Load composer
require __DIR__.'/../vendor/autoload.php';

use App\Core\Environment;
Use App\Core\Route;

// Load environment here
Environment::load();

// Start Route system
$init = new Route();
$init->start();
	
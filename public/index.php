<?php

	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	/**
	 * Se requeire la configuración del sistema
	 */
	require_once "config.php";

	/**
	 * Load app controllers
	 */
	require __DIR__.'/../app/app_autoloader.php';
	/**
	 * Load composer
	 */
	require __DIR__.'/../vendor/autoload.php';
	Use App\Core\route;
	$init = new route();
	$init->start();
	
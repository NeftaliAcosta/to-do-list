<?php

namespace App\Core;

use \PDO;
use \PDOException;

/**
 * Conetar 
 *
 * Clase que controla PDO para conectar a la base de datos
 *
 * @author Neftalí Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2021, NEFTALI ACOSTA
 * @link https://gubynetwork.com
 * @version 1.0
 */

class Conexion{
	/**
	 * Nombre del usuario de la base de datos
	 * @access private
	 * @var string
	 */
	private $dbuser;

	/**
	 * Nombre de la base de datos
	 * @access private
	 * @var string
	 */
	private $dbname;

    /**
     * Nombre del password de la base de datos
     * @access private
     * @var string
     */
    private $dbpassword;

	/**
     * Nombre del host de la base de datos
     * @access private
     * @var string
     */
	private $dbhost;

    public function __construct(
		string $usuario,
		string $database,
		string $password,
		string $host
	){
		$this->dbuser = $usuario;
		$this->dbname = $database;
		$this->dbpassword = $password;
		$this->dbhost = $host;
	}

	public function conectar() :object
	{
		$dsn = "mysql:host=".$this->dbhost.";dbname=".$this->dbname.";charset=utf8";

		try {
			$mbd = new PDO($dsn, $this->dbuser, $this->dbpassword, array(
					PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'", 
					PDO::ATTR_PERSISTENT => true
				)
			);
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->exec('SET NAMES "utf8"');
		} catch (PDOException $e) {
			echo $e->getMessage();
			return false;
		}
		return $mbd;
	}


}

<?php

namespace App\Core;

use Exception;
use PDO;
use PDOException;

/**
 * Connection
 * Class that controls Connection to connect to the database
 *
 * @author Neftalí Marciano Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2024, Neftali Marciano Acosta
 * @link https://www.linkedin.com/in/neftaliacosta
 * @version 1.0
 */

class Connection
{
    /**
     * Database username
     * @access private
     * @var string
     */
    private string $dbuser;

    /**
     * Database name
     * @access private
     * @var string
     */
    private string $dbname;

    /**
     * Database password
     * @access private
     * @var string
     */
    private string $dbpassword;

    /**
     * Database hostname
     * @access private
     * @var string
     */
    private string $dbhost;


    private static ?object $instance = null;

    public function __construct(
        string $dbuser,
        string $dbname,
        string $dbpassword,
        string $dbhost
    ) {
        $this->dbuser = $dbuser;
        $this->dbname = $dbname;
        $this->dbpassword = $dbpassword;
        $this->dbhost = $dbhost;
    }

    /**
     * It is used to clone the objects of another instance
     *
     * @return mixed
     * @throws Exception
     */
    public function __clone()
    {
        throw new Exception("Can't clone a singleton of " . get_class($this));
    }

    /**
     * Connect to our Database (Default to MySQL server)
     *
     * @return PDO|null
     * @throws coreException
     */
    public function connect(): PDO|null
    {
        $dsn = "mysql:host=" . $this->dbhost . ";dbname=" . $this->dbname . ";charset=utf8";

        if (self::$instance == null) {
            try {
                self::$instance = new PDO($dsn, $this->dbuser, $this->dbpassword, [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                    PDO::ATTR_PERSISTENT => true
                ]);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->exec('SET NAMES "utf8"');
            } catch (PDOException) {
                throw  new CoreException("Error connecting to database server");
            }
        }

        return self::$instance;
    }
}

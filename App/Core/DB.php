<?php

namespace App\Core;

use App\Core\MySql\MySql;
use App\Libs\Tools;
use PDOException;

/**
 * DB (Database transaction)
 * Class made to process transactions with our DB
 *
 * @author Neftalí Marciano Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2024, Neftali Marciano Acosta
 * @link https://www.linkedin.com/in/neftaliacosta
 * @version 1.0
 */
class DB
{
    /**
     * Which table are we going to use for transactions
     *
     * @var string
     */
    private string $aliasTable = '';

    /**
     * Table identifier to work with
     *
     * @var int
     */
    private int $id;

    public function __construct(string $alias_table, int $id)
    {
        $this->aliasTable = $alias_table;
        $this->id = $id;
    }

    /**
     * Get last version of database
     *
     * @return array|bool
     */
    public static function getVersion(): array|bool
    {
        $oMySql = new MySql();
        $data = $oMySql->custom("SELECT version, modified_at FROM versions");
        try {
            $version = $data->execute();
        } catch (PDOException) {
            self::init();
            $version = $data->execute();
        }
        return $version['data'];
    }

    /**
     * Create the version control table
     *
     * @return void
     */
    public static function init(): void
    {
        $oMySql = new MySql();
        $oMySql->custom("
            CREATE TABLE IF NOT EXISTS 
                versions
            (
                version VARCHAR(255),
                modified_at BIGINT
            )");
        $oMySql->execute();
    }

    /**
     * Set the current version in the database
     *
     * @param int $version
     * @return void
     */
    public static function setVersion(int $version): void
    {
        $oMySql = new MySql();
        if ($version < 2) {
            $custom_query = "
                INSERT INTO 
                    versions 
                (
                    version, 
                    modified_at
                ) 
                VALUES 
                (
                  " . $version . ",
                  " . time() . "
                )";
        } else {
            $custom_query = "
                UPDATE 
                    versions
                SET modified_at = " . time() . ",
                    version = " . $version;
        }
        $oMySql->custom($custom_query);
        $oMySql->execute();
    }

    /**
     * Set value for a specific column in our database using updateString() method
     *
     * @param string $parameter
     * @param string $value
     * @param array|null $v_wh
     * @return void
     */
    public function setString(string $parameter, string $value, array $v_wh = null): void
    {
        if ($value != "") {
            // Object to access database
            $oMySql = new MySql();
            $update = $oMySql->update();

            // $v_wh Variable for where clause in the query
            if (empty($v_wh)) {
                $v_wh = [
                    'id = ?' => [
                        'type' => 'int',
                        'value' => Tools::scInt($this->id),
                    ],
                ];
            }

            // Query in data base
            $update->updateString($parameter, Tools::scStr($value))->from($this->aliasTable)->where($v_wh)->execute();
        }
    }

    /**
     * Set column value in the database using updateInt() method
     *
     * @param string $parameter
     * @param int $value
     * @param array|null $v_wh
     * @return void
     */
    public function setInt(string $parameter, int $value, array $v_wh = null): void
    {
        if ($value != 0) {
            $oMySql = new MySql();
            $update = $oMySql->update();

            // $where clause is being defined just here
            if (empty($v_wh)) {
                $v_wh = [
                    'id = ?' => [
                        'type' => 'int',
                        'value' => Tools::scInt($this->id),
                    ],
                ];
            }

            // Set new value in our database table
            $update->updateInt($parameter, Tools::scInt($value))->from($this->aliasTable)->where($v_wh)->execute();
        }
    }

    /**
     * * Set column value in the database using updateFloat() method
     *
     * @param array|null $v_wh
     */
    public function setFloat(string $parameter, float $value, array $v_wh = null): void
    {
        if ($value != 0) {
            $oMySql = new MySql();
            $update = $oMySql->update();


            // $where clause is being defined just here
            if (empty($v_wh)) {
                $v_wh = [
                    'id = ?' => [
                        'type' => 'int',
                        'value' => $this->id,
                    ],
                ];
            }

            // Set new value in our database table
            $update->updatefloat($parameter, Tools::scFloat($value))->from($this->aliasTable)->where($v_wh)->execute();
        }
    }

    /**
     * Set status value in the database
     *
     * @param string $parameter
     * @param int $value
     * @param array|null $v_wh
     * @return void
     */
    public function setStatus(string $parameter, int $value, array $v_wh = null): void
    {
        if ($value >= 0 && $value <= 1) {
            $oMySql = new MySql();
            $update = $oMySql->update();

            // $where clause is being defined just here
            if (empty($v_wh)) {
                $v_wh = [
                    'id = ?' => [
                        'type' => 'int',
                        'value' => Tools::scInt($this->id),
                    ],
                ];
            }

            // Set new value in our database table
            $update->updateInt($parameter, Tools::scInt($value))->from($this->aliasTable)->where($v_wh)->execute();
        }
    }
}
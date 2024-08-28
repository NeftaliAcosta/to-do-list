<?php

namespace App\Core\Container;

use App\Core\Container\Exception\TableNotFoundException;
use App\Core\coreException;

/**
 * Container
 * Saves the relationship between the original database and the model alias
 *
 * @author Neftalí Marciano Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2024, Neftali Marciano Acosta
 * @link https://www.linkedin.com/in/neftaliacosta
 * @version 1.0
 */
class Container{

    /**
     * Alias tables dictionary
     *
     * @var array $tables
     */
    public static array $tables = array(
        'users' => 'entity_users',
        'tasks' => 'entity_tasks'
    );

    /**
     * Gets the actual name of the table in the database
     *
     * @param string $table
     * @return string
     */
    public static function getTable(string $table): string
    {
        try {
            if (array_key_exists($table, self::$tables)) {
                return self::$tables[$table];
            }else{
                throw new TableNotFoundException('The alias table does not exist.');
            }
        } catch (CoreException $e) {
            echo $e->errorMessage();
        }
    }

}
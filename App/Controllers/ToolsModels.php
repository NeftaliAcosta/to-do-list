<?php

namespace App\Controllers;

use App\Core\MySql\MySql;


/**
 * ToolsModels
 * Magic methods for models
 *
 * @author Neftalí Marciano Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2024, Neftali Marciano Acosta
 * @link https://www.linkedin.com/in/neftaliacosta
 * @version 1.0
 *
 */
class ToolsModels
{
    private string $aliasTable;
    private MySql $oMySql;

    public function __construct(string $aliasTable)
    {
        // Set alias table for query's
        $this->aliasTable = $aliasTable;

        // Object to access database
        $this->oMySql = new MySql();
    }

    /**
     * Get UUID from id
     *
     * @param string $uuid
     * @return integer
     */
    public function getIdFromUuid(string $uuid): int
    {
        $vWhere = [
            "uuid = ?" => [
                'type' => "string",
                'value' => $uuid
            ]
        ];

        $response = $this->oMySql
            ->select('id')
            ->from($this->aliasTable)
            ->where($vWhere)
            ->execute();

        return $response['data']['id'] ?? 0;
    }
}
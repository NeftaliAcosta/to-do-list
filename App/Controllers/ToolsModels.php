<?php

namespace App\Controllers;

use App\Core\CoreException;
use App\Core\MySql\MySql;

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
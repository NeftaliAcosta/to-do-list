<?php

namespace App\Core\MySql;

use App\Core\Connection;
use App\Core\Container\Container;
use App\Core\CoreException;
use App\Core\MySql\Exception\IncorrectCustomStructureException;
use App\Core\MySql\Exception\IncorrectDataStructureException;
use App\Core\MySql\Exception\IncorrectDeleteStructureException;
use App\Core\MySql\Exception\IncorrectInsertStructureException;
use App\Core\MySql\Exception\IncorrectSortValueException;
use App\Core\MySql\Exception\IncorrectUpdateStructureException;
use App\Core\MySql\Exception\IncorrectWhereStructureException;
use App\Core\MySql\Exception\QueryMethodNotSelectedException;
use App\Core\MySql\Exception\QueryTableNotSelectedException;
use PDO;

/**
 * MySql
 * Proprietary driver for MySQL
 *
 * @author Neftalí Marciano Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2024, Neftali Marciano Acosta
 * @link https://www.linkedin.com/in/neftaliacosta
 * @version 1.0
 */
class MySql
{
    /**
     * Causes a select query to return only one record
     * @var boolean
     */
    private bool $fetch = true;

    /**
     * Causes a select query to return more than one record
     * @var boolean
     */
    private bool $fetch_all = false;

    /**
     * Used in the select query, contains the selected fields
     * @var string
     */
    private string $selected_fields = "";

    /**
     * It is used in the update query, it contains the fields to update
     * @var string
     */
    private string $fields_to_update = "";

    /**
     * It is used in the insert query, it contains the fields to insert
     * @var string
     */
    private string $fields_to_insert = "";

    /**
     * It is used in the insert query, it contains the values to insert
     * @var string
     */
    private string $values_to_insert = "";

    /**
     * Query table
     * @var string
     */
    private string $table = "";

    /**
     * Query condition
     * @var string
     */
    private string $query_condition = "";

    /**
     * Query type
     * @var string
     */
    private string $query_type = "";

    /**
     * Query prepare
     * @var string
     */
    private string $query = "";

    /**
     * Prepare a custom query
     * @var string
     */
    private string $custom_query = "";

    /**
     * Instance of PDO Class
     * @var object
     */
    private object $oPDO;

    /**
     * Sort query results
     * @var string
     */
    private string $sort_structure = "";

    /**
     * Field to sort
     * @var string
     */
    private string $sort_field;

    /**
     * Sort type
     * @var string
     */
    private string $sort_type;

    /**
     * Flag that saves if you need to sort the query results
     * @var bool
     */
    private bool $flag_sort = false;


    /**
     * Result limit
     * @var int
     */
    private int $limit_results = 0;

    /**
     * Flag that saves if the results have a limit
     * @var bool
     */
    private bool $flag_limit = false;


    /**
     * @throws coreException
     */
    public function __construct()
    {
        $pdo_temp = new Connection(
            $_ENV['DB_USER'],
            $_ENV['DB_NAME'],
            $_ENV['DB_PASS'],
            $_ENV['DB_HOST']
        );

        // Instance of PDO controller
        $this->oPDO = $pdo_temp->connect();

        // Use our database
        $this->custom("USE " . $_ENV['DB_NAME'])->execute();
    }

    /**
     * Defines a query of type "SELECT"
     *
     * @param string $select Selected fields separated by ','
     * @return MySql
     */
    public function select(string $select="*"): MySql
    {
        $this->selected_fields = $select;
        $this->query_type = "SELECT";

        return $this;
    }

    /**
     * Defines a query of type "UPDATE"
     *
     * @return MySql
     */
    public function update(): MySql
    {
        $this->query_type = 'UPDATE';

        return $this;
    }

    /**
     * Defines a query of type "DELETE"
     *
     * @return MySql
     */
    public function delete(): MySql
    {
        $this->query_type = 'DELETE';

        return $this;
    }

    /**
     * Defines a query of type "INSERT" and set values
     *
     * @param array $data Array of objects with the data to insert
     * @return MySql
     * @example  ['user' => ['type' => 'string', 'value' => 'demo'], 'privilege' => ['type' => 'numeric', 'value' => 1]];
     */
    public function insert(array $data): MySql
    {

        $this->query_type = 'INSERT';
        $this->fields_to_insert = '(';
        $this->values_to_insert = '(';

        // Query construction
        foreach ($data as $clave => $valor) {
            try {
                if(!isset($valor['type'])){
                    throw new IncorrectDataStructureException(
                        'Incorrect data structure in INSERT query.'
                    );
                }

                if(!isset($valor['value'])){
                    throw new IncorrectDataStructureException(
                        'Incorrect data structure in INSERT query.'
                    );
                }

                $this->fields_to_insert .= "{$clave},";

                if($valor['type']=="numeric"){
                    $this->values_to_insert .= "{$valor['valor']},";
                }else{
                    $this->values_to_insert .= "'{$valor['valor']}',";
                }

            } catch (CoreException $e) {
                echo $e->errorMessage();
            }
        }

        $this->fields_to_insert = rtrim($this->fields_to_insert, ",");
        $this->values_to_insert = rtrim($this->values_to_insert, ",");
        $this->fields_to_insert .= ')';
        $this->values_to_insert .= ')';

        return $this;
    }

    /**
     * Set an integer type field to an UPDATE type query
     *
     * @param string $campo Field name
     * @param integer $value Field value
     * @return MySql
     */
    public function updateInt(string $campo, int $value): MySQL
    {
        $this->fields_to_update .= "{$campo} = {$value}, ";

        return $this;
    }

    /**
     * Set a field of type string to a query of type UPDATE
     *
     * @param string $campo Field name
     * @param string $value Field value
     * @return MySql
     */
    public function updateString(string $campo, string $value): MySql
    {
        $this->fields_to_update .= "{$campo} = '{$value}', ";
        return $this;
    }

    /**
     * Set a field of type float to a query of type UPDATE
     *
     * @param string $field
     * @param float $value
     * @return $this
     */
    public function updateFloat(string $field, float $value): MySql
    {
        $this->update_parameters .= "`$field` = $value, ";
        return $this;
    }

    /**
     * Set a custom query
     *
     * @param string $mysql_query
     * @return MySql
     */
    public function custom(string $mysql_query): MySql
    {
        $this->query_type = "CUSTOM";
        $this->custom_query = $mysql_query;
        return $this;
    }

    /**
     * Defines the name of the alias table (It must be declared in the table container)
     *
     * @param string $alias_table Alias table name
     * @return object
     * @example entity_users
     */
    public function from(string $alias_table): object
    {
        $container = new Container;
        $this->table = $container->getTable($alias_table);

        return $this;
    }

    /**
     * Defines the condition of the query to execute and sets the values
     *
     * @param array $values Array containing the condition
     * @return MySql
     * @example [ 'user like >%<' =>[ 'type' => 'string', 'value' => 'JHON', "separator" => 'AND' ], 'registration = ?' => [ 'type' => 'string', 'value' => '2018-09-06 12:04:12' ]];
     */
    public function where(array $values): MySql
    {

        // Query prepare
        foreach ($values as $clave => $value) {
            try {
                // Check structure
                if(!isset($value['type'])){
                    throw new IncorrectWhereStructureException('The TYPE parameter of the condition has not been specified.');
                }

                if(!isset($value['value'])){
                    throw new IncorrectWhereStructureException('The VALUE parameter of the condition has not been specified');
                }

                // Query construction
                if($value['type']=="numeric"){
                    $this->query_condition .= str_replace('?', $value['value'], $clave);
                }else{
                    if(strpos($clave, '%')>0){
                        $this->query_condition .= str_replace("%", $value['value'], $clave);
                        $this->query_condition = str_replace(">", "'%", $this->query_condition);
                        $this->query_condition = str_replace("<", "%'", $this->query_condition);
                    }else{
                        $this->query_condition .= str_replace('?', "'".$value['value']."'", $clave);
                    }
                }

                if(isset($value['separator'])){
                    $this->query_condition .= ' '.$value['separator']. ' ';
                }else{
                    break;
                }

            } catch (CoreException $e) {
                echo $e->errorMessage();
            }
        }

        return $this;
    }

    /**
     * Define a single record as return in the SELECT query
     *
     * @return MySql
     */
    public function fetch(): MySql
    {
        $this->fetch = true;
        $this->fetch_all = false;

        return $this;
    }

    /**
     * Define multiple records as return in SELECT query
     *
     * @return MySql
     */
    public function fetchAll(): MySql
    {
        $this->fetch_all = true;
        $this->fetch = false;

        return $this;
    }

    /**
     * Set the ordering of the results
     *
     * @param string $field
     * @param string $sort_type
     * @return MySql
     * @throws IncorrectSortValueException
     */
    public function orderBy(string $field, string $sort_type): MySql
    {
        if ($sort_type != 'ASC' || $sort_type != 'DESC') {
            throw new IncorrectSortValueException('The sort value is incorrect.');
        }

        $this->sort_type = $sort_type;
        $this->sort_field = $field;
        $this->sort_structure = $this->sort_field.' '.$this->sort_type;

        return $this;
    }

    /**
     * Defines the data limit that the sql query must have
     *
     * @param int $limit
     * @return object
     */
    public function limit(int $limit): object
    {
        $this->limit_results = $limit;

        return $this;
    }

    /**
     * Prepare the query before it is executed
     *
     * @return void
     */
    private function prepare(): void
    {
        try {
            switch ($this->query_type) {
                case 'SELECT':
                    $this->prepareSelect();
                    break;

                case 'UPDATE':
                    $this->prepareUpdate();
                    break;

                case 'DELETE':
                    $this->prepareDelete();
                    break;

                case 'INSERT':
                    $this->prepareInsert();
                    break;

                case 'CUSTOM':
                    $this->prepareCustom();
                    break;

                default:
                    throw new QueryMethodNotSelectedException(
                        'The type of query to perform has not been specified.'
                    );
                    break;
            }
        } catch (CoreException $e) {
            echo $e->errorMessage();
        }

    }

    /**
     * Prepare the query and sample. Useful for debugging
     *
     * @return void
     */
    public function prepareTest(): void
    {
        $this->prepare();

        try {
            throw new CoreException("{$this->query}");
        } catch (CoreException $e) {
            echo $e->errorMessage();
        }
    }

    /**
     * Execute the query using the PDO controller
     *
     * @param bool $fetchType
     * @param bool $returnLastId
     * @param bool $countTotalRows
     * @return array|null
     */
    public function execute(bool $returnLastId = false, bool $countTotalRows = false): ?array
    {
        $this->prepare();
        $response = [];
        try {
            if ($this->query !=='' || $this->custom_query !== '') {
                $stmt = $this->oPDO->prepare($this->query);
                $stmt->execute();

                $response['resp'] = true;

                if ($this->query_type=='SELECT' || $this->query_type=="CUSTOM") {

                    if ($this->fetch) {
                        $response['data'] = $stmt->fetch();
                    } else{
                        $response['data'] = $this->fetch_all ? $stmt->fetchAll(PDO::FETCH_CLASS) : $stmt->fetchAll();
                    }

                    if ($countTotalRows) {
                        $response['totalRows'] = $stmt->rowCount();
                    }

                    if ($returnLastId) {
                        $response['lastInsert'] = (int) $this->oPDO->lastInsertId();
                    }

                } else if($this->query_type=='UPDATE' || $this->query_type=="DELETE") {
                    $response['rowCount'] = $stmt->rowCount();
                }

            } else {
                throw new QueryMethodNotSelectedException(
                    'The type of query to perform has not been specified.'
                );
            }
        } catch (CoreException $e) {
            echo $e->errorMessage();
        }
        return $response;
    }

    /**
     * Logic to prepare a SELECT query
     *
     * @return void
     */
    private function prepareSelect(): void
    {
        try {
            if($this->table!==''){

                if($this->query_condition!=='' && $this->sort_structure == false && $this->limit_results == false){

                    $this->query = "SELECT {$this->selected_fields} FROM {$this->table} WHERE {$this->query_condition};";

                }elseif(!empty($this->query_condition) && $this->sort_structure == true && $this->limit_results == false){

                    $this->query = "SELECT {$this->selected_fields} FROM {$this->table} ORDER BY {$this->sort_structure}; ";

                }elseif(!empty($this->query_condition) && $this->sort_structure == true && $this->limit_results == true){

                    $this->query = "SELECT {$this->selected_fields} FROM {$this->table} ORDER BY {$this->sort_structure} LIMIT {$this->limit_results}; ";

                }else{
                    $this->query = "SELECT {$this->selected_fields} FROM {$this->table};";
                }

            }else{
                throw new QueryTableNotSelectedException(
                    "The table was not specified in the from() method of the query {$this->query_type}"
                );
            }

        } catch (CoreException $e) {
            echo $e->errorMessage();
        }
    }

    /**
     * Logic to prepare a UPDATE query
     *
     * @return void
     */
    private function prepareUpdate(): void
    {
        try {

            $this->fields_to_update = trim($this->fields_to_update);
            $this->fields_to_update = rtrim($this->fields_to_update, ',');

            if($this->fields_to_update!==''){

                if($this->table!==''){

                    if($this->query_condition!==''){

                        $this->query = "UPDATE {$this->table} SET {$this->fields_to_update} WHERE {$this->query_condition};";

                    }else{
                        throw new IncorrectUpdateStructureException(
                            "One or more CONDITIONS were not specified in the where() method
                            of the query {$this->query_type}"
                        );
                    }

                }else{
                    throw new IncorrectUpdateStructureException(
                        "The TABLE was not specified in the from() method of the query{$this->query_type}"
                    );
                }

            }else{
                throw new IncorrectUpdateStructureException(
                    "The fields to update have not been specified in the query's updateString()
                    method {$this->query_type}"
                );
            }

        } catch (IncorrectUpdateStructureException $e) {
            echo $e->errorMessage();
        }
    }

    /**
     * Logic to prepare a DELETE query
     *
     * @return void
     */
    private function prepareDelete(): void
    {
        try {
            if($this->table!==''){

                if($this->query_condition!==''){

                    $this->query = "DELETE FROM {$this->table} WHERE {$this->query_condition};";

                }else{
                    throw new IncorrectDeleteStructureException(
                        "One or more CONDITIONS were not specified in the where() 
                        method of the query {$this->query_type}"
                    );
                }

            }else{
                throw new IncorrectDeleteStructureException(
                    "The TABLE was not specified in the from() method of the query {$this->query_type}"
                );
            }

        } catch (IncorrectDeleteStructureException $e) {
            echo $e->errorMessage();
        }
    }

    /**
     * Logic to prepare a INSERT query
     *
     * @return void
     */
    private function prepareInsert(): void
    {
        try {
            if($this->fields_to_insert!=="" AND $this->values_to_insert!==""){
                if($this->table!==''){

                    $this->query = "INSERT INTO {$this->table} {$this->fields_to_insert} VALUES {$this->values_to_insert};";

                }else{
                    throw new IncorrectInsertStructureException(
                        "The TABLE was not specified in the from() method of the query {$this->query_type}"
                    );
                }

            }else{
                throw new IncorrectInsertStructureException(
                    "Values to insert have not been specified in the insert() 
                    method of the query {$this->query_type}"
                );
            }

        } catch (IncorrectInsertStructureException $e) {
            echo $e->errorMessage();
        }
    }

    /**
     * Prepare a custom query
     *
     * @return void
     */
    private function prepareCustom(): void
    {
        try {

            if($this->custom_query !== ""){
                $this->query = $this->custom_query;
            }else{
                throw new IncorrectCustomStructureException(
                    "No query specified on type: {$this->query_type}"
                );
            }

        } catch (IncorrectCustomStructureException $e) {
            echo $e->errorMessage();
        }
    }

}
<?php

namespace App\Core;

use App\Core\Conexion;
use App\Controllers\Container;
use App\Core\SystemException;

/**
 * Sql 
 *
 * Clase encapsulada que realiza consultas a la base de datos utulizando PDO
 * Ayuda a que las consultas que haga el modelo sean más estéticas y denifidas
 *
 * @author Neftalí Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2021, NEFTALI ACOSTA
 * @link https://gubynetwork.com
 * @version 1.0
 */
 
class Sql
{
    /**
     * Hace que una consulta select regrese solo un registro
     *
     * @var boolean
     */
    private bool $fetch = true;
    /**
     * Hace que una consulta select regrese más de un registro
     *
     * @var boolean
     */
    private bool $fetchAll = false;
    /**
     * Se utiliza en la consulta select, contiene los campos seleccionados
     * @var string
     */
    private string $parametrosSeleccionados = "";
    /**
     * Se utiliza en la consulta update, contiene los campos a actualizar
     *
     * @var string
     */
    private string $parametrosActualizados = "";
    /**
     * Se utiliza en la consulta insert, contiene los campos a insertar
     *
     * @var string
     */
    private string $camposInsertar = "";
    /**
     * Se utiliza en la consulta insert, contiene los valores a insertar
     *
     * @var string
     */
    private string $valoresInsertar = "";
    /**
     * Guarda la tabla de la consulta
     *
     * @var string
     */
    private string $tabla = "";
    /**
     * Guarda la condición de la consulta
     *
     * @var string
     */
    private string $condicion = "";
    /**
     * Guarda el tipo de consulta (select, update, delete, insert)
     *
     * @var string
     */
    private string $tipo = "";
    
    /**
     * Contiene la consulta preparada
     *
     * @var string
     */
    private string $consulta = "";

    /**
     * Prepara una consulta personalizada
     *
     * @var string
     */
    private string $custom = "";
    /**
     * Objecto que se utiliza para instanciar PDO
     *
     * @var object
     */
    private object $PDO;

     /**
     * Guarda la estructura de la sentencia sql
     *
     * @var string
     */
    private string $o = "";

     /**
     * Contiene el nombre de la tabla utilizar para orden la consulta
     *
     * @var string
     */
    private string $objeto;

    /**
     * Determina el orden de la consulta
     *
     * @var string
     */
    private string $orden;

    /**
     * Guarda el orden
     *
     * @var bool
     */
    private bool $order = false;

    /**
     * Guarda el orden objeto
     *
     * @var string
     */
    private string $order_object = "";

    /**
     * Guarda el valor del orden
     *
     * @var string
     */
    private string $order_value = "";

    /**
     * Guarda el limite de la consulta sql
     *
     * @var int
     */
    private int $limite = 0;

    /**
     * Guarda si existe un limite o no
     *
     * @var string
     */
    private bool $limit = false;

    /**
     * Guarda el valor del limite
     *
     * @var int
     */
    private int $limit_value = 0;
    

    public function __construct(string $database = 'crm')
    {
        if ($database = "") {
            throw new SystemException('Selecciona una base de datos.');
        }

        $pdo_temp = new Conexion(
            'root',
            $database,
            '',
            'localhost'
        );

        $this->PDO = $pdo_temp->conectar();
    }

    /**
     * Define una consulta del tipo SELECT
     *
     * @param string $select Campos seleccionados separados por ,
     * @return object
     */
    public function select(string $select="*"): object
    {
        $this->parametrosSeleccionados = $select;
        $this->tipo = "select";
        return $this;
    }

    /**
     * Define una consulta del tipo UPDATE
     *
     * @return object
     */
    public function update(): object
    {
        $this->tipo = 'update';
        return $this;
    }

    /**
     * Define ua consulta del tipo DELETE
     *
     * @return object
     */
    public function delete(): object
    {
        $this->tipo = 'delete';
        return $this;
    }

    /**
     * Define una consulta del tipo INSERT y setea los valores a INSERTAR
     *
     * @param array $data Array de objetos con los datos a insertar
     * @example  ['usuario' => ['tipo' => 'string', 'valor' => 'taito'], 'email' => ['tipo' => 'string', 'valor' => 'taitomore@gmail.com']];
     * @return object
     */
    public function insert(array $data): object
    {

        $this->tipo = 'insert';
        $this->camposInsertar = '(';
        $this->valoresInsertar = '(';

        foreach ($data as $clave => $valor) {
            
            try {
                if(!isset($valor['tipo'])){
                    throw new SystemException('El parámetro TIPO de la consulta no se ha especificado.');
                }

                if(!isset($valor['valor'])){
                    throw new SystemException('El parámetro VALOR de la consulta no se ha especificado.');
                }

                $this->camposInsertar .= "{$clave},";

                if($valor['tipo']=="numeric"){
                    $this->valoresInsertar .= "{$valor['valor']},";
                }else{
                    $this->valoresInsertar .= "'{$valor['valor']}',";
                }

            } catch (SystemException $e) {
                echo $e->errorMessage();
            }

        }

        $this->camposInsertar = rtrim($this->camposInsertar, ",");
        $this->valoresInsertar = rtrim($this->valoresInsertar, ",");

        $this->camposInsertar .= ')';
        $this->valoresInsertar .= ')';

        return $this;
    }

    /**
     * Setea un campo de tipo entero a una consulta del tipo UPDATE
     *
     * @param string $campo Nombre del campo
     * @param integer $value Valor del campo
     * @return object
     */
    public function updateInt(string $campo, int $value): object
    {
        $this->parametrosActualizados .= "{$campo} = {$value}, ";
        return $this;
    }

    /**
     * Setea un campo de tipo string a una consulta del tipo UPDATE
     *
     * @param string $campo Nombre del campo
     * @param string $value Valor del campo
     * @return object
     */
    public function updateString(string $campo, string $value): object
    {
        $this->parametrosActualizados .= "{$campo} = '{$value}', ";
        return $this;
    }

    /**
     * Sete una consulta personalizada
     *
     * @param string $sql
     * @return object
     */
    public function custom(string $sql): object
    {
        $this->tipo = "custom";
        $this->custom = $sql;
        return $this;
    }
    /**
     * Define el nombre OBJETO que contiene la tabla en donde se aplicará la consulta ejecutada.
     * para poder ejecutarlo debe estar declarado en containerController.php
     *
     * @param string $tabla Nombre del objeto que contiene la tabla
     * @example crm_usuarios
     * @example job_usuarios
     * @return object
     */
    public function from(string $tabla): object
    {
        $container = new Container;
        $this->tabla = $container->getTabla($tabla);
        return $this;
    }

    /**
     * Define la condición de la consulta a ejecutar y setea los valores
     *
     * @param array $values Array que contiene la condición
     * @example [ 'usuario like >%<' =>[ 'tipo' => 'string', 'valor' => 'CHELA', "separador" => 'AND' ], 'registro = ?' =>[ 'tipo' => 'string', 'valor' => '2018-09-06 12:04:12' ] ];
     * @return object
     */
    public function where(array $values): object
    {
        
        foreach ($values as $clave => $valor) {

            try {
                if(!isset($valor['tipo'])){
                    throw new SystemException('El parámetro TIPO de la condición no se ha especificado.');
                }

                if(!isset($valor['valor'])){
                    throw new SystemException('El parámetro VALOR de la condición no se ha especificado.');
                }

                if($valor['tipo']=="numeric"){
                    $this->condicion .= str_replace('?', $valor['valor'], $clave);
                }else{

                    if(strpos($clave, '%')>0){

                        $this->condicion .= str_replace("%", $valor['valor'], $clave);
                        $this->condicion = str_replace(">", "'%", $this->condicion);
                        $this->condicion = str_replace("<", "%'", $this->condicion);

                    }else{
                        $this->condicion .= str_replace('?', "'".$valor['valor']."'", $clave);
                    }
                    
                }
                
                if(isset($valor['separador'])){
                    $this->condicion .= ' '.$valor['separador']. ' ';
                }else{
                    break;
                }

            } catch (SystemException $e) {
                echo $e->errorMessage();
            }

            
        } 
        return $this;
    }

    /**
     * Define un solo registro como retorno en la consulta SELECT
     *
     * @return object
     */
    public function fetch(): object
    {
        $this->fetch = true;
        $this->fetchAll = false;
        return $this;
    }

    /**
     * Define varios registros como retonor en la contula SELECT
     *
     * @return object
     */
    public function fetchAll(): object
    {
        $this->fetchAll = true;
        $this->fetch = false;
        return $this;
    }
    
    /**
     * Define el orden de la consulta sql. 
     * El metodo recibe dos parametros tipo string $orden y $objeto
     * @param string $orden 
     * @param string $objeto
     * @return object
     */
    public function orderBy(string $orden, string $objeto): object
    {   
        $this->orden = $orden;
        $this->objeto = $objeto;
        
        $this->o = $this->objeto.' '.$this->orden;
        
        return $this;
    }

    /**
     * Define el limite de datos que debe traer la consulta sql
     * @param int $limite
     * @return object
     */
    public function limit(int $limite): object
    {   
        $this->limite = $limite;
        
        return $this;
    }

    /**
     * Prepara la consulta SQL según su tipo. La preparación se hace de manera
     * automática al ejectuar el métofo execute()
     *
     * @return object
     */
    private function prepare(): object
    {
        try {
            switch ($this->tipo) {
                case 'select':
                    $this->prepareSelect();
                    break;
                    
                case 'update':
                    $this->prepareUpdate();
                    break;
                
                case 'delete':
                    $this->prepareDelete();
                    break;

                case 'insert':
                    $this->prepareInsert();
                    break;
                
                case 'custom':
                    $this->prepareCustom();
                    break;
                
                default:
                    throw new SystemException('No se ha especificado el tipo de consulta a realizar.');
                    break;
            }
        } catch (SystemException $e) {
            echo $e->errorMessage();
        }
        return $this;
    }

    /**
     * Prepara una consulta inicializada y la muestra el string de la consuilta en 
     * pantalla pero no la ejecuta en MYSQL. No es necesario ejecutar el método execute().
     *
     * @return void
     */
    public function prepareTest(): void
    {
       $this->prepare();

        try {
           throw new SystemException("{$this->consulta}");
        } catch (SystemException $e) {
            echo $e->errorMessage(true);
        } 
    }

    /**
     * Es necesario para ejecutar una consulta MYSQL.
     *
     * @return array|null
     */
    public function execute(): ?array
    {
        $this->prepare();
        $respuesta = [];
        try {
            if($this->consulta !=='' || $this->custom !== ''){
                $stmt = $this->PDO->prepare($this->consulta);
                $stmt->execute();

                $respuesta['resp'] = true;

                if($this->tipo=='select' || $this->tipo=="custom"){

                    if($this->fetch){
                        $respuesta['data'] = $stmt->fetch();
                    }else{
                        $respuesta['data'] = $stmt->fetchAll();
                    }

                }else if($this->tipo=='update' || $this->tipo=="delete"){
                    $respuesta['rowCount'] = $stmt->rowCount();
                }

            }else{
                throw new SystemException("No se ha especificado el método prepare() de la consulta {$this->tipo}");
            }
        } catch (SystemException $e) {
            echo $e->errorMessage();
        }
        return $respuesta;
    }

    /**
     * Prepara una consulta de tipo SELECT
     *
     * @return void
     */
    private function prepareSelect(): void
    {
        try {
            if($this->tabla!==''){  

                if($this->condicion!=='' && $this->o == false && $this->limite == false){

                    $this->consulta = "SELECT {$this->parametrosSeleccionados} FROM {$this->tabla} WHERE {$this->condicion};";

                }elseif(!empty($this->condicion) && $this->o == true && $this->limite == false){
                    
                    $this->consulta = "SELECT {$this->parametrosSeleccionados} FROM {$this->tabla} ORDER BY {$this->o}; ";
                
                }elseif(!empty($this->condicion) && $this->o == true && $this->limite == true){
                    
                    $this->consulta = "SELECT {$this->parametrosSeleccionados} FROM {$this->tabla} ORDER BY {$this->o} LIMIT {$this->limite}; ";
                
                }else{
                    $this->consulta = "SELECT {$this->parametrosSeleccionados} FROM {$this->tabla};";
                }

            }else{
                throw new SystemException("No se ha especificado la TABLA en el método from() de la consulta {$this->tipo}");
            }

        } catch (SystemException $e) {
            echo $e->errorMessage();
        }
    }

    /**
     * Prepara una consulta de tipo UPDATE
     *
     * @return void
     */
    private function prepareUpdate(): void
    {
        try {
            
            $this->parametrosActualizados = trim($this->parametrosActualizados);
            $this->parametrosActualizados = rtrim($this->parametrosActualizados, ',');
            
            if($this->parametrosActualizados!==''){

                if($this->tabla!==''){

                    if($this->condicion!==''){

                        $this->consulta = "UPDATE {$this->tabla} SET {$this->parametrosActualizados} WHERE {$this->condicion};";
    
                    }else{
                        throw new SystemException("No se ha especificado una o más CONDICIONES en el método where() de la consulta {$this->tipo}");
                    }

                }else{
                    throw new SystemException("No se ha especificado la TABLA en el método from() de la consulta {$this->tipo}");
                }

            }else{
                throw new SystemException("No se ha especificado los campos a actualizar el método updatrInt() | updateString de la consulta {$this->tipo}");
            }

        } catch (SystemException $e) {
            echo $e->errorMessage();
        }
    }

    /**
     * Prepara una consulta de tipo DELETE
     *
     * @return void
     */
    private function prepareDelete(): void
    {
        try {
            
            if($this->tabla!==''){

                if($this->condicion!==''){

                    $this->consulta = "DELETE FROM {$this->tabla} WHERE {$this->condicion};";

                }else{
                    throw new SystemException("No se ha especificado una o más CONDICIONES en el método where() de la consulta {$this->tipo}");
                }

            }else{
                throw new SystemException("No se ha especificado la TABLA en el método from() de la consulta {$this->tipo}");
            }
            
        } catch (SystemException $e) {
            echo $e->errorMessage();
        }
    }

    /**
     * Prepara una consulta de tipo INSERT
     *
     * @return void
     */
    private function prepareInsert(): void
    {
        try {
            
            if($this->camposInsertar!=="" AND $this->valoresInsertar!==""){
                if($this->tabla!==''){

                    $this->consulta = "INSERT INTO {$this->tabla} {$this->camposInsertar} VALUES {$this->valoresInsertar};";
    
                }else{
                    throw new SystemException("No se ha especificado la TABLA en el método from() de la consulta {$this->tipo}");
                }

            }else{
                throw new SystemException("No se han especificado los valores a insertsr en el método insert() de la consulta {$this->tipo}");
            }
            
        } catch (SystemException $e) {
            echo $e->errorMessage();
        }
    }

    private function prepareCustom(): void
    {
        try {
            
            if($this->custom !== ""){
                $this->consulta = $this->custom;
            }else{
                throw new SystemException("No se ha especificado ninguna consulta en el tipo: {$this->tipo}");
            }
            
        } catch (SystemException $e) {
            echo $e->errorMessage();
        }
    }
}
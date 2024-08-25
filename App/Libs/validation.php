<?php

namespace App\Core;

/**
 * Validation 
 *
 * Clase que se utiliza para validar parámetros de entrada
 *
 * @author Neftalí Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2021, NEFTALI ACOSTA
 * @link https://gubynetwork.com
 * @version 2.0
 */
 
class Validation
{
    
    /**
     * @var array $patterns
     */
    public $patterns = array(
        'uri'           => '[A-Za-z0-9-\/_?&=]+',
        'url'           => '[A-Za-z0-9-:.\/_?&=#]+',
        'alpha'         => '[\p{L}]+',
        'words'         => '[\p{L}\s]+',
        'alphanum'      => '[\p{L}0-9]+',
        'int'           => '[0-9]+',
        'float'         => '[0-9\.,]+',
        'tel'           => '[0-9+\s()-]+',
        'text'          => '[\p{L}0-9\s-.,;:!"%&()?+\'°#\/@]+',
        'file'          => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+\.[A-Za-z0-9]{2,4}',
        'folder'        => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+',
        'address'       => '[\p{L}0-9\s.,()°-]+',
        'date_dmy'      => '[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}',
        'date_ymd'      => '[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}',
        'email'         => '[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+[.]+[a-z-A-Z]'
    );
    
    /**
     * @var array $errors
     */
    public $errors = array();
    
    /**
     * Nombre del campo
     * 
     * @param string $name
     * @return object $this
     */
    public function name(string $name): object
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Valor del campo
     * 
     * @param string $value
     * @return object $this
     */
    public function value(string $value): object
    {
        $this->value = $value;
        return $this;
    }
    
    /**
     * File
     * 
     * @param mixed $value
     * @return object $this
     */
    public function file(mixed $value): object
    {   
        $this->file = $value;
        return $this;
    }
    
    /**
     * Pattern a aplicar
     * 
     * @param string $name Nombre del Pattern
     * @return object $this
     */
    public function pattern(string $name): object
    {
        if($name == 'array'){
            
            if(!is_array($this->value)){
                $this->errors[] = 'El formato del campo: '.$this->name.' no es válido.';
            }
        
        }else{
            $regex = '/^('.$this->patterns[$name].')$/u';
            if($this->value != '' && !preg_match($regex, $this->value)){
                $this->errors[] = 'El formato del campo: '.$this->name.' no es válido.';
            }
            
        }
        return $this;  
    }
    
    /**
     * Pattern personalizado
     * 
     * @param string $pattern Estructura personalizada del Pattern
     * @return object $this
     */
    public function customPattern(string $pattern): object
    {
        $regex = '/^('.$pattern.')$/u';
        if($this->value != '' && !preg_match($regex, $this->value)){
            $this->errors[] = 'El formato del campo: '.$this->name.' no es válido.';
        }
        return $this;   
    }

    /**
     * Define que un campo es obligatorio
     * 
     * @return object $this
     */
    public function required(): object
    {
        if((isset($this->file) && $this->file['error'] == 4) || ($this->value == '' || $this->value == null)){
            $this->errors[] = 'El campo: '.$this->name.' es obligatorio.';
        }            
        return $this;
    }
    
    /**
     * Tamaño mínimo del campo
     * 
     * @param int $length es el tamaño minimo
     * @return object this
     */
    public function min(int $length): object
    {
        if(is_string($this->value)){

            if(strlen($this->value) < $length){
                $this->errors[] = 'El valor del campo: '.$this->name.' es inferior al mínimo';
            }
        }else{

            if($this->value < $length){
                $this->errors[] = 'El valor del campo: '.$this->name.' es inferior al mínimo';
            }
        }

        return $this;  
    }
        
    /**
     * Tamaño máximo del campo
     * 
     * @param int $length es el tamaño maximo
     * @return object $this
     */
    public function max(int $length): object
    {    
        if(is_string($this->value)){
            
            if(strlen($this->value) > $length){
                $this->errors[] = 'El valor del campo: '.$this->name.' es superior al mínimo';
            }
        }else{
            
            if($this->value > $length){
                $this->errors[] = 'El valor del campo: '.$this->name.' es superior al mínimo';
            }
            
        }
        return $this;
    }
    
    /**
     * Tamaño máximo del campo
     * 
     * @param mixed $value
     * @return object $this
     */
    public function equal(mixed $value): object
    {
        if($this->value != $value){
            $this->errors[] = 'El valor del campo: '.$this->name.' no es igual al correspondiente.';
        }
        return $this;
    }
    
    /**
     * Dimensione massima del file 
     *
     * @param int $size
     * @return object $this 
     */
    public function maxSize(int $size): object
    {    
        if($this->file['error'] != 4 && $this->file['size'] > $size){
            $this->errors[] = 'El archivo: '.$this->name.' supera el tamño máximo de '.number_format($size / 1048576, 2).' MB.';
        }
        return $this;  
    }
    
    /**
     * Extensión del archivo
     *
     * @param string $extension
     * @return object $this 
     */
    public function ext(string $extension): object
    {
        if($this->file['error'] != 4 && pathinfo($this->file['name'], PATHINFO_EXTENSION) != $extension && strtoupper(pathinfo($this->file['name'], PATHINFO_EXTENSION)) != $extension){
            $this->errors[] = 'El archivo: '.$this->name.' no tiene la extensión: '.$extension.'.';
        }
        return $this;
    }
    
    /**
     * Purifica para prevenir ataques XSS
     *
     * @param string $string
     * @return string $string
     */
    public function purify(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Verifica que los campos sean validadados correctamente
     * 
     * @return boolean
     */
    public function isSuccess(): bool
    {
        if(empty($this->errors)){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Muestra los errores de la validación
     * 
     * @return array $this->errors
     */
    public function getErrors(): array
    {
        if(!$this->isSuccess()) return $this->errors;
    }
    
    /**
     * Muestra los errores de la validación en formato HTML
     * 
     * @return string $html
     */
    public function getErrorsHTML(): string
    {   
        $html = '<ul>';
            foreach($this->getErrors() as $error){
                $html .= '<li>'.$error.'</li>';
            }
        $html .= '</ul>';
        
        return $html;
    }
    
    /**
     * Muestra el resultado de la  validación
     *
     * @return boolean|string
     */
    public function result(): ?string
    {
        if(!$this->isSuccess()){
            foreach($this->getErrors() as $error){
                echo "$error\n";
            }
            exit;
        }else{
            return true;
        }
    }
    
    /**
     * Comprueba si el valor es un número entero
     *
     * @param string $value
     * @return boolean
     */
    public static function is_int(string $value): bool
    {
        if(filter_var($value, FILTER_VALIDATE_INT)){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Comprueba si el valor es un flotante
     *
     * @param mixed $value
     * @return boolean
     */
    public static function is_float(mixed $value): bool
    {
        if(filter_var($value, FILTER_VALIDATE_FLOAT)) return true;
    }
    
    /**
     * Comprueba si el valor son puras letras del alfabeto
     *
     * @param string $value
     * @return boolean
     */
    public  function is_alpha(string $value): bool
    {
        if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z]+$/")))){ 
            return true;
        }else{ 
            $this->errors[] = 'El campo "'.$this->name.'" no tiene caracteres validos.';
            return false;
        }
    }
    
    /**
     * Comprueba si el valor son puras letras del alfabeto y/o numeros
     *
     * @param string $value
     * @return boolean
     */
    public function is_alphanum(string $value): bool
    {
        if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")))){ 
            return true;
        }else{ 
            $this->errors[] = 'El campo "'.$this->name.'" no tiene caracteres validos.';
            return false;
        }
    }

    /**
     * Comprueba si el valor es un telefono
     *
     * @param mixed $value
     * @return boolean
     */
    public static function is_tel(mixed $value): bool
    {
        if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[0-9+\s()-]+$/")))) return true;
    }
    
    /**
     * Comprueba si el valor es una URL
     *
     * @param mixed $value
     * @return boolean
     */
    public static function is_url(mixed $value): bool
    {
        if(filter_var($value, FILTER_VALIDATE_URL)) return true;
    }
    
    /**
     * Comprueba si el valor es una URI
     *
     * @param mixed $value
     * @return boolean
     */
    public static function is_uri(mixed $value): bool
    {
        if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[A-Za-z0-9-\/_]+$/")))) return true;
    }
    
    /**
     * Comprueba si el valor es un booleano
     *
     * @param mixed $value
     * @return boolean
     */
    public static function is_bool(mixed $value): bool
    {
        if(is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) return true;
    }
    
    /**
     * Verifica si el valor es un correo electrónico
     *
     * @param string $value
     * @return boolean
     */
    public function is_email(string $value): bool
    {
        if(filter_var($value, FILTER_VALIDATE_EMAIL)){
            return true;
        }else{ 
            $this->errors[] = 'El campo "'.$this->name.'" no tiene caracteres validos.';
            return false;
        }
    }
}
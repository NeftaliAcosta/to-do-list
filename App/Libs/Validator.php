<?php

namespace App\Libs;

/**
 * Validator
 * Class used to validate input parameters
 *
 * @author Neftalí Marciano Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2024, Neftali Marciano Acosta
 * @link https://www.linkedin.com/in/neftaliacosta
 * @version 1.0
 */
class Validator
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
    public array $errors = array();

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $value;

    /**
     * @var mixed
     */
    private mixed $file;

    /**
     * @var bool
     */
    private bool $required;

    /**
     * Field name
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
     * Field value
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
     * Custom pattern rule to apply
     *
     * @param string $pattern Pattern rule
     * @return Validator
     */
    public function customPattern(string $pattern): Validator
    {
        if ($this->value != null && !preg_match($pattern, $this->value)) {
            $this->errors[$this->name][] = "The format of field {$this->name} is not valid.";
        }
        return $this;
    }

    /**
     * Defines that a field is required (Need a value other than empty or null)
     *
     * @param bool $required
     * @return $this
     */
    public function required(bool $required = true): Validator
    {
        if ($this->value == null && $required) {
            $this->errors[$this->name][] = "The field {$this->name} is required.";
        }

        $this->required = $required;

        return $this;
    }

    /**
     * Minimum field value
     *
     * @param int $min
     * @return Validator
     */
    public function min(int $min): Validator
    {
        if ($this->value != null) {
            if ((int)$this->value < $min) {
                $this->errors[$this->name][] = 'The value of the field is less than the minimum.';
            }
        }

        return $this;
    }

    /**
     * Maximum field value
     *
     * @param int $max
     * @return Validator
     */
    public function max(int $max): Validator
    {
        if ($this->value != null) {
            if ((int)$this->value > $max) {
                $this->errors[$this->name][] = 'The value of the field is greater than the minimum.';
            }
        }

        return $this;
    }

    /**
     * Length minimum
     *
     * @param int $min
     * @return Validator this
     */
    public function minLength(int $min): Validator
    {
        if (strlen($this->value) < $min) {
            $this->errors[] = "Field value {$this->name} is less than minimum.";
        }

        return $this;
    }

    /**
     * Length maximum
     *
     * @param int $max
     * @return Validator $this
     */
    public function maxLength(int $max): Validator
    {
        if(strlen($this->value) > $max){
            $this->errors[] = "Field value {$this->name} is greater than minimum";
        }

        return $this;
    }

    /**
     * Value equal to
     *
     * @param mixed $value
     * @return Validator $this
     */
    public function equal(mixed $value): Validator
    {
        if($this->value != $value){
            $this->errors[] = "The value of the field {$this->name} is not equal to the corresponding one.";
        }

        return $this;
    }

    /**
     * Maximum size
     *
     * @param int $size
     * @return object $this
     */
    public function maxSize(int $size): object
    {
        if($this->file['error'] != 4 && $this->file['size'] > $size){
            $this->errors[] = "The file size of {$this->name} is larger than expected.";
        }

        return $this;
    }

    /**
     * File extension
     *
     * @param string $extension
     * @return Validator $this
     */
    public function ext(string $extension): Validator
    {
        if(
            $this->file['error'] != 4 &&
            pathinfo($this->file['name'], PATHINFO_EXTENSION) != $extension &&
            strtoupper(pathinfo($this->file['name'], PATHINFO_EXTENSION)) != $extension
        ) {
            $this->errors[] = "The file extension of {$this->name} is not the same as expected.";
        }

        return $this;
    }

    /**
     * Check if the value is an integer
     *
     * @return Validator
     */
    public function is_int(): Validator
    {
        if($this->value != null && !filter_var($this->value, FILTER_VALIDATE_INT)){
            $this->errors[] = "The field {$this->name} has no valid characters.";
        }

        return $this;
    }

    /**
     * Check if the value is a float
     *
     * @param mixed $value
     * @return Validator
     */
    public function is_float(mixed $value): Validator
    {
        if(!filter_var($value, FILTER_VALIDATE_FLOAT)) {
            $this->errors[] = "The field {$this->name} has no valid characters.";
        }

        return $this;
    }

    /**
     * Check if the value is pure letters of the alphabet
     *
     * @return Validator
     */
    public function is_alpha(): Validator
    {
        if(!filter_var($this->value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[a-zA-ZáÁéÉíÍóÓúÚüÜñÑ\s]+$/u')))){
            $this->errors[] = "The field {$this->name} has no valid characters.";
        }

        return $this;
    }

    /**
     * Check if the value is pure letters of the alphabet and/or numbers
     *
     * @return Validator
     */
    public function is_alphanum(): Validator
    {
        if(!filter_var($this->value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")))){
            $this->errors[] = "The field {$this->name} has no valid characters.";
        }

        return $this;
    }

    /**
     * Check if the value is a phone
     *
     * @param mixed $value
     * @return Validator
     */
    public function is_tel(mixed $value): Validator
    {
        if(!filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[0-9+\s()-]+$/")))) {
            $this->errors[] = "The field {$this->name} has no valid value.";
        }

        return $this;
    }

    /**
     * Check if the value is a URL
     *
     * @param mixed $value
     * @return Validator
     */
    public function is_url(mixed $value): Validator
    {
        if(!filter_var($value, FILTER_VALIDATE_URL)) {
            $this->errors[] = "The field {$this->name} has no valid value.";
        }

        return $this;
    }

    /**
     * Check if the value is a URI
     *
     * @param mixed $value
     * @return Validator
     */
    public function is_uri(mixed $value): Validator
    {
        if(!filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[A-Za-z0-9-\/_]+$/")))) {
            $this->errors[] = "The field {$this->name} has no valid value.";
        }

        return $this;
    }

    /**
     * Check if the value is a boolean
     *
     * @param mixed $value
     * @return Validator
     */
    public function is_bool(mixed $value): Validator
    {
        if(!is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))){
            $this->errors[] = "The field {$this->name} has no valid value.";
        }

        return $this;
    }

    /**
     * Check if the value is an email
     *
     * @param string $value
     * @return Validator
     */
    public function is_email(string $value): Validator
    {
        if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
            $this->errors[] = "The field {$this->name} has no valid value.";
        }

        return $this;
    }

    public function inArray(array $enum_array): Validator
    {
        $a_values = array_values($enum_array);
        if (!in_array($this->value, $a_values)) {
            $this->errors[] = "The field {$this->name} has no valid value.";
        }

        return $this;
    }

    /**
     * Verify that the fields are validated correctly
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
     * Show validation errors
     *
     * @return array $this->errors
     */
    public function getErrors(): array
    {
        if(!$this->isSuccess()) return $this->errors;
    }

    /**
     * Displays validation errors in HTML format
     *
     * @return string $html
     */
    public function getErrorsHTML(): string
    {
        $html = '<ul>';
        foreach($this->getErrors() as $error){
            if (is_string($error)){
                $html .= '<li>'.$error.'</li>';
            }
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Displays the validation result
     *
     * @return boolean|string
     */
    public function result(): bool|string
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

}
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
    public function lengthMin(int $min): Validator
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
    public function lengthMax(int $max): Validator
    {
        if(strlen($this->value) > $max){
            $this->errors[] = "Field value {$this->name} is greater than minimum";
        }

        return $this;
    }

    /**
     * Validate a password with minimum eight characters, at least one letter and one number:
     *
     * @return Validator
     */
    public function isPassword(): Validator
    {
        if ($this->value != null) {
            if (
                !filter_var(
                    $this->value,
                    FILTER_VALIDATE_REGEXP,
                    ['options' => ['regexp' => "/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/"]]
                )
            ) {
                $this->errors[] = "Field value {$this->name} is not a valid password";
            }
        }
        return $this;
    }

    /**
     * Check if the value is an integer
     *
     * @return Validator
     */
    public function isInt(): Validator
    {
        if($this->value != null && !filter_var($this->value, FILTER_VALIDATE_INT)){
            $this->errors[] = "The field {$this->name} has no valid characters.";
        }

        return $this;
    }

    /**
     * Check if the value is pure letters of the alphabet
     *
     * @return Validator
     */
    public function isAlpha(): Validator
    {
        if ($this->value != null) {
            if (!filter_var($this->value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => "/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/"]])) {
                $this->errors[] = "The field {$this->name} has no valid value.";
            }
        }

        return $this;
    }

    public function isAlphaNumeric(): Validator
    {
        if ($this->value != null) {
            if (!filter_var($this->value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => "/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s0-9]+$/"]])) {
                $this->errors[] = "The field {$this->name} has no valid value.";
            }
        }

        return $this;
    }

    /**
     * Check if the value is an email
     *
     * @return Validator
     */
    public function isEmail(): Validator
    {
        if ($this->value != null) {
            if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = "The field {$this->name} has no valid email.";
            }
        }

        return $this;
    }

    /**
     * Check if the value is a uuid string valid
     *
     * @return Validator
     */
    public function isUuidValid(): Validator
    {
        if ($this->value != null) {
            $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';

            if (preg_match($pattern, $this->value) !== 1) {
                $this->errors[] = "The field {$this->name} has no valid uuid.";
            }
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
        if (empty($this->errors)) {
            return true;
        } else {
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
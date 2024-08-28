<?php

namespace App\Libs;

use App\Core\CoreException;
use App\Models\User\Exception;

/**
 * Tools
 * Contains tools used by the app
 *
 * @author Neftalí Marciano Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2024, Neftali Marciano Acosta
 * @link https://www.linkedin.com/in/neftaliacosta
 * @version 1.0
 * @version 1.0
 */
class Tools
{
    /**
     * Scape a string before insert in database
     *
     * @param string|null $string $string
     * @return string
     */
    public static function scStr(string|null $string): string
    {
        $string = is_null($string) ? '' : $string;
        $string = trim($string);
        return addslashes($string);
    }

    /**
     * Scape an int before insert in database
     *
     * @param int $int
     * @return int
     */
    public static function scInt(mixed $int): int
    {
        return (int)$int;
    }

    /**
     * Scape a float before insert in database
     *
     * @param mixed $float
     * @return float
     */
    public static function scFloat(mixed $float): float
    {
        return (float)$float;
    }

    /**
     * Get uuid random id
     *
     * @return string
     * @throws CoreException
     */
    public static function getUUID(): string
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        try {
            $data = random_bytes(16);
        } catch (Exception) {
            throw new CoreException("Error generating random value.");
        }

        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * @param string $password
     * @return string
     */
    public static function hash(string $password): string
    {
        $options = [
            'cost' => 12,
        ];

        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    /**
     * Verify hash
     *
     * @param string $password
     * @param string $hashedPassword
     * @return bool
     */
    public static function hashVerify(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }

    /**
     * Get a date modify
     *
     * @param string $timeFuture
     * @return string
     */
    public static function date(string $timeFuture = ''): string
    {
        date_default_timezone_set("America/Mexico_City");
        if ($timeFuture == '') {
            return date("Y-m-d H:i:s");
        } else {
            $fecha = date('Y-m-d H:i:s');
            return date('Y-m-d H:i:s', strtotime($timeFuture, strtotime($fecha)));
        }
    }

    /**
     * Convert timestamp to date
     *
     * @param int $date
     * @return string
     */
    public static function strToDate(int $date): string
    {
        return date('Y-m-d H:i:s', $date);
    }

    /**
     * Return today date without hours
     *
     * @return string
     */
    public static function dateWithOutHours(): string
    {
        date_default_timezone_set("America/Mexico_City");
        return date("Y-m-d");
    }

    /**
     * Add specific date
     *
     * @param string $add
     * @return string
     */
    public static function modifyDateToCurrent(string $add): string
    {
        $today = date("Y-m-d");
        return date("Y-m-d", strtotime($today . $add));
    }
}
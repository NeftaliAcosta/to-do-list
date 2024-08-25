<?php

namespace App\Core;

use Dotenv\Dotenv;

/**
 * Environment
 * Class made to work with environment
 *
 * @author Neftalí Marciano Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2024, Neftali Marciano Acosta
 * @link https://www.linkedin.com/in/neftaliacosta
 * @version 1.0
 */

class Environment
{
    /**
     * Get our environment to work
     *
     * @return void
     */
    public static function get(): void
    {
        $env = Environment::getEnvironment();
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../', '.env.' . $env);
        $dotenv->load();
    }

    /**
     * @return string
     */
    private static function getEnvironment(): string
    {
        return getenv('TODOAPP_ENVIRONMENT');
    }

    /**
     * Load system environment
     *
     * @return void
     */
    public static function load(): void
    {
        /**
         * Set environment to work with
         */
        self::set();

        /**
         * Get environment
         */
        self::get();
    }

    /**
     * Check variables environment to load
     *
     * @return void
     */
    public static function set(): void
    {
        $cli_env = getopt('e:', ['environment::']);
        $env = 'dev';
        if (!empty($cli_env['e'])) {
            $env = $cli_env['e'];
        } elseif (!empty($cli_env['environment'])) {
            $env = $cli_env['environment'];
        }
        $env = strtolower(trim($env));
        putenv('TODOAPP_ENVIRONMENT=' . $env);
    }

    /**
     * Check dev environment
     *
     * @return bool
     */
    public static function isDev(): bool
    {
        return getenv('TODOAPP_ENVIRONMENT') == 'dev';
    }
}

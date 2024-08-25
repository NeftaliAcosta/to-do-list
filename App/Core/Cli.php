<?php

namespace App\Core;

use App\Enums\ConsoleBackgroundColors;
use App\Enums\ConsoleForegroundColors;

/**
 * Cli
 * Class written to print nicely in the system console
 *
 * @author Neftalí Marciano Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2024, Neftali Marciano Acosta
 * @link https://www.linkedin.com/in/neftaliacosta
 * @version 1.0
 */
class Cli
{
    /**
     * Print some value in the console
     *
     * @param string $message
     * @param ConsoleForegroundColors $foregroundColor Font Color
     * @param ConsoleBackgroundColors|null $backgroundColor Background Color
     *
     * @return void
     */
    public static function e(
        string $message,
        ConsoleForegroundColors $foregroundColor,
        ?ConsoleBackgroundColors $backgroundColor = null
    ): void {
        print "";
        $background = '';
        if (!is_null($backgroundColor)) {
            $background = ";" . $backgroundColor->value;
        }

        $foreground = $foregroundColor->value ?? ConsoleForegroundColors::Default->value;
        print "\e[0;" . $foreground . $background . "m" . $message . "\e[0m\n";
    }
}
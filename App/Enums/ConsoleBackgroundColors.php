<?php

namespace App\Enums;

/**
 * ConsoleBackgroundColors
 * Class written to give to our console some colored backgrounds applied to our texts
 *
 * @author Neftalí Marciano Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2024, Neftali Marciano Acosta
 * @link https://www.linkedin.com/in/neftaliacosta
 * @version 1.0
 */
enum ConsoleBackgroundColors : string
{
    case Black = '40';
    case Red = '41';
    case Green = '42';
    case Yellow = '43';
    case Blue = '44';
    case Magenta = '45';
    case Cyan = '46';
    case Light_Gray = '47';
}
<?php

namespace App\Enums;

/**
 * ConsoleColors
 * Class written to give to our console some colored texts
 *
 * @author Neftalí Marciano Acosta <neftaliacosta@outlook.com>
 * copyright (c) 2024, Neftali Marciano Acosta
 * @link https://www.linkedin.com/in/neftaliacosta
 * @version 1.0
 */
enum ConsoleForegroundColors : string
{
    case Default = '39';
    case Black = '30';
    case Red = '31';
    case Green = '32';
    case Yellow = '33';
    case Blue = '34';
    case Magenta = '35';
    case Cyan = '36';
    case Light_Gray = '37';
    case Dark_Gray = '90';
    case Light_Red = '91';
    case Light_Green = '92';
    case Light_Yellow = '93';
    case Light_Blue = '94';
    case Light_Magenta = '95';
    case Light_Cyan = '96';
    case White = '97';
}
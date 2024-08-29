<?php

// Add table `users`

use App\Core\Cli;
use App\Core\Container\Container;
use App\Core\MySql\MySql;
use App\Enums\ConsoleForegroundColors;

/** @var MySql $oMysql */

// Set table name from Container
$table = Container::getTable('users');

// Print message
Cli::e(
    "Creating Table `{$table}`",
    ConsoleForegroundColors::Green
);

// Execute script
$oMysql->custom("
    CREATE TABLE `{$table}` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `uuid` binary(36) NOT NULL,
        `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
        `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
        `password` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
        `registrationDate` datetime NOT NULL,
        `lastLoginDate` datetime DEFAULT NULL,
        `verified` tinyint NOT NULL DEFAULT 1,
        `status` tinyint NOT NULL DEFAULT 1,
        PRIMARY KEY (`id`),
        UNIQUE (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
")->execute();
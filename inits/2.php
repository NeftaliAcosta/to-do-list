<?php

// Add table `tasks`

use App\Core\Cli;
use App\Core\Container\Container;
use App\Core\MySql\MySql;
use App\Enums\ConsoleForegroundColors;

/** @var MySql $oMysql */

// Set table name from Container
$tasksTable = Container::getTable('tasks');
$usersTable = Container::getTable('users');


// Print message
Cli::e(
    "Creating Table `{$tasksTable}`",
    ConsoleForegroundColors::Green
);

// Execute script
$oMysql->custom("
    CREATE TABLE `{$tasksTable}` (
        id INT AUTO_INCREMENT NOT NULL,
        uuid BINARY(36) NOT NULL,
        title VARCHAR(50) NOT NULL,
        description VARCHAR(200) NOT NULL,
        status INT NOT NULL,
        creationDate DATETIME NOT NULL,
        user_id INT NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (user_id) REFERENCES {$usersTable}(id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
    )
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8mb4
    COLLATE=utf8mb4_general_ci;
")->execute();
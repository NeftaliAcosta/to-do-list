<?php

/**
 * Init system file
 * To migrate our database and run a specific environment
 *
 * @author Neftalí Marciano Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2024, Neftali Marciano Acosta
 * @link https://www.linkedin.com/in/neftaliacosta
 * @version 1.0
 */

use App\Core\Cli;
use App\Core\Container\Container;
use App\Core\CoreException;
use App\Core\DB;
use App\Core\Environment;
use App\Core\MySql\MySql;
use App\Enums\ConsoleBackgroundColors;
use App\Enums\ConsoleForegroundColors;

// Load composer
require __DIR__.'/../vendor/autoload.php';

// Load environment to work
Environment::load();

// Enable error reporting in dev environment
if (Environment::isDev()) {
    // Show errors
    error_reporting(-1);
    error_reporting(E_ALL);
    ini_set('error_reporting', E_ALL);
}

Cli::e(
    "++++++++++ Checking our database version ++++++++++",
    ConsoleForegroundColors::Black,
    ConsoleBackgroundColors::Yellow
);

// Creating a new sql instance with a useful database connection
$oMysql = new MySql();

// Add table container
$container = new Container();

try {
    // Folder to execute all init files from system migrations
    $init_path = glob(__DIR__ . '/../inits/*.php');
    natsort($init_path);

    // Check for last modified file in the init folder
    $files = array_combine($init_path, array_map("basename", $init_path));
    // Sort all files in our directory in a descending manner
    arsort($files);
    $versions = str_replace(".php", "", $files);

    // Check if exist files in "inits" folder
    if (empty($versions)) {
        exit;
    }
    // Get last version from files in the folder
    $last_file = array_search(max($versions), $versions);
    // Get current database version
    $version = DB::getVersion();
    // If we don't have a version then we'll create a new record with our first init record
    // iterate over all init files to be executed by our migration system
    foreach ($init_path as $filename) {
        // Extract filename to compare with our current database version
        $init_file = preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($filename));
        if (isset($version['version'])) {
            if ($version['version'] >= $init_file) {
                // Do not run previous versions to our current DB version
                continue;
            }
        }
        // Printing init file version
        Cli::e(
            "++++++++++ Processing init file => " . $init_file . " ++++++++++",
            ConsoleForegroundColors::Magenta
        );
        // Including each init file to be executed by our system
        include $filename;
        print("\n");
        // Set version processed in our DB table of versions
        DB::setVersion((int)$init_file);
    }
    Cli::e(
        "++++++++++ Database is up to date :) ++++++++++",
        ConsoleForegroundColors::Black,
        ConsoleBackgroundColors::Yellow
    );
} catch (CoreException $e) {
    // Show error in case of exception thrown
    print("There was an error: " . $e->getMessage());
}
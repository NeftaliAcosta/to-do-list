<?php

namespace  App\Core;

use Buki\Router\Router;
use Exception;

/**
 * Route
 * Load the route controller into the system
 *
 * @author Neftalí Marciano Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2024, Neftali Marciano Acosta
 * @link https://www.linkedin.com/in/neftaliacosta
 * @version 1.0
 */
class Route{
    /**
     * Instance of Route controller
     * @var Router
     */
    private Router $route;

    /**
     * @var string|array
     */
    private string|array $ext;

    /**
     * @var string|false
     */
    private string|bool $last;


    public function __construct(){

        // Route controller instance
        $this->route =  new Router([
            'paths' => [
                'Controllers' => 'App/Controllers',
                'Middlewares' => 'App/Middlewares',
            ],
            'namespaces' => [
                'Controllers' => 'App\Controllers',
                'Middlewares' => 'App\Middlewares',
            ],
        ]);


        $url = $_SERVER["REQUEST_URI"];
        $keys = parse_url($url); // parse the url
        $path = explode("/", $keys['path']); // splitting the path
        $last = end($path); // get the value of the last element

        $this->ext = pathinfo($last,PATHINFO_EXTENSION);
        $this->last=$last;

    }

    /**
     * Initialize the main page by default
     *
     * @return void
     */
    public function start(): void
    {
        require_once __DIR__ . '/../Templates/default.php';
    }

    /**
     * Load all required paths
     *
     * @throws Exception
     */
    public function pages(): void
    {
        // Set home directory
        $base = __DIR__.'/../';

        // Define the folders to include
        $folders = [
            'Routes'
        ];

        // Iterate through the folders and include the corresponding files
        foreach ($folders as $f) {
            foreach (glob($base . "$f/*.php") as $k => $file) {
                require_once $file;
            }
        }
        $this->route->run();
    }

}
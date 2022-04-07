<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

session_start();
/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('recherche', ['controller' => 'Home', 'action' => 'recherche']);
$router->add('rechercheavancee', ['controller' => 'Home', 'action' => 'rechercheavancee']);
$router->add('Home', ['controller' => 'Home', 'action' => 'index']);


$router->add('{controller}/{action}');

$router->add('{controller}/{action}/{id:\d+}');
    
$router->dispatch($_SERVER['QUERY_STRING']);

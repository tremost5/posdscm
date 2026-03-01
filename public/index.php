<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Resolve Application Base Path
|--------------------------------------------------------------------------
|
| On shared hosting, the public directory can live outside the project
| root. We try local default first, then fallback to your deployment path.
|
*/
$appBasePath = dirname(__DIR__);

if (! is_file($appBasePath.'/vendor/autoload.php')) {
    $appBasePath = '/home/dscx7887/posdscm';
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $appBasePath.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $appBasePath.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $appBasePath.'/bootstrap/app.php';

$app->handleRequest(Request::capture());

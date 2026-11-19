<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

try {
    require __DIR__.'/../vendor/autoload.php';

    $app = require __DIR__.'/../bootstrap/app.php';

    $storage = sys_get_temp_dir().'/inventory-laravel';
    foreach (['framework/cache/data', 'framework/sessions', 'framework/views'] as $dir) {
        $path = $storage.'/'.$dir;
        if (! is_dir($path)) {
            mkdir($path, 0775, true);
        }
    }

    $app->useStoragePath($storage);
    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain');
    echo get_class($e).': '.$e->getMessage()."\n".$e->getFile().':'.$e->getLine();
}
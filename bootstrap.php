<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();


$settings = require __DIR__ . '/config/settings.php';

$app = new \Slim\App($settings);

//dependencies
require  __DIR__ . '/config/dependencies.php';

//middleware
require __DIR__ . '/routes/middleware.php';

//inlui rotas
foreach (glob(__DIR__ .'/routes/*.php') as $filename) {
    require_once $filename;
}


$app->run();
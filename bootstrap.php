<?php

require __DIR__ . '/vendor/autoload.php';


$settings = require __DIR__ . '/config/settings.php';

$container = new \Slim\Container($settings);

//configuração do eloquent
$container['db'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['db']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

$container['db'];

$app = new \Slim\App($container);

//middleware
require __DIR__ . '/routes/middleware.php';

//inlui rotas
foreach (glob(__DIR__ .'/routes/*.php') as $filename) {
    require_once $filename;
}


$app->run();
<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$settings = require __DIR__ . '/config/settings.php';

$app = new \Slim\App($settings);


require  __DIR__ . '/config/dependencies.php';

$pdo = $container['db']->getConnection()->getPdo();

return [
    'paths' => [
        'migrations' => __DIR__.'/src/db/migrations'
    ],
    'environments' => [
        'default_database' => 'development',
        'development' => [
            'name' => $settings['settings']['db']['database'],
            'connection' => $pdo
        ]
    ]
];
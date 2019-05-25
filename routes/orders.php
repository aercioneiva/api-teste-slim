<?php

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;



$app->group("/v1/orders", function() use ($app) {

    $this->get("", 'App\Controllers\OrderController:index');

    $this->post("", 'App\Controllers\OrderController:create');

    $this->put("/{id:[0-9]+}", 'App\Controllers\OrderController:update');
});


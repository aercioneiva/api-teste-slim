<?php

$app->group("/v1/products", function() use ($app) {
    $this->get("", 'App\Controllers\ProductController:index');
    $this->post("", 'App\Controllers\ProductController:create'); 
});


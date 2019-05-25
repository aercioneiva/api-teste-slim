<?php


$app->group("/v1/customers", function() use ($app) {
    $this->post("", 'App\Controllers\CustomerController:create'); 
});


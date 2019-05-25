<?php

//cors
$app->add(function($request,$response,$next){
    $response = $next($request,$response);

    return $response
        ->withHeader('Access-Control-Allow-Origin','*')
        ->withHeader('Access-Control-Allow-Headers','Content-Type,Authorization,Origin,Accept')
        ->withHeader('Access-Control-Allow-Methods','GET,POST,PUT');
});
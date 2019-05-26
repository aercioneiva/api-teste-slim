<?php
use \Firebase\JWT\JWT;
//cors
$app->add(function($request,$response,$next){
    $response = $next($request,$response);

    return $response
        ->withHeader('Access-Control-Allow-Origin','*')
        ->withHeader('Access-Control-Allow-Headers','Content-Type,Authorization,Origin,Accept')
        ->withHeader('Access-Control-Allow-Methods','GET,POST,PUT');
});

//auth
$auth = function ($request, $response, $next) {

   if (!$request->hasHeader("Authorization")) {
        return $response->withJson(['error' => 'token is invalid'],401);
    }

    $token = $request->getHeaderLine("Authorization");
    try {
        $jwt = JWT::decode($token, env('JWT_SCRET'), array("HS256"));
    } catch (Exception $e) {
        return $response->withJson(['error' => 'token is invalid'],401);
    }

    $response = $next($request, $response);
    return $response;
};
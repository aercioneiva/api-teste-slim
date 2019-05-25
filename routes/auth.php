<?php

use App\Models\Customer;
use Firebase\JWT\JWT;

$app->post('/v1/auth/token', function($request, $response, $args){
    $data = $request->getParsedBody();
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;

    $customer = Customer::where('email',$email)->first();

    if($customer && password_verify($password,$customer->password)){
        $jwt = new stdClass;
        $jwt->id = $customer->id;
        $jwt->name = $customer->name;
        $jwt->email = $customer->email;
        $jwt->exp = time() + (60 * 60);;

        return $response->withJson([
            'token' => JWT::encode($jwt,env('JWT_SCRET'))
        ]);
    }

    return $response->withjson(['error' => 'email or password is invalid'],401);
});
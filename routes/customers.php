<?php

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Models\Customer;
use Webmozart\Assert\Assert;
use InvalidArgumentException;
use  Illuminate\Database\QueryException;

$app->group("/v1/customers", function() use ($app) {

    $this->post("", function(Request $request, Response $response, $args = []) use ($app) {
        $data = $request->getParsedBody();

        if(!validaCPF($data['cpf'])){
            return $response->withJson(['error' => 'cpf invalido']);
        }

        try {
            Assert::keyExists($data, 'cpf', 'cpf is required');
            Assert::notEmpty($data['cpf'], 'cpf is required');
            Assert::keyExists($data, 'name', 'name is required');
            Assert::notEmpty($data['name'], 'name is required');
            Assert::keyExists($data, 'email', 'email is required');
            Assert::greaterThan($data['email'], 'email is required');
            
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('email is invalid');
            } 
            
            if(Customer::where('email', $data['email'])->orWhere('cpf', $data['cpf'])->first()){
                throw new InvalidArgumentException('email or cpf already exists');
            }
            $customer = Customer::create($data);
            return $response->withJson($customer);
        } catch (InvalidArgumentException $e) {
            return $response->withJson(['error' => $e->getMessage()]);
        } catch (QueryException $e) {
            return $response->withJson(['error' => $e->getMessage()]);
        }

        
    });

    
});


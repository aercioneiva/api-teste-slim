<?php
namespace App\Controllers;

use App\Models\Customer;
use Webmozart\Assert\Assert;
use InvalidArgumentException;
use  Illuminate\Database\QueryException;

class CustomerController{

    public function create($request, $response, $args){
        $data = $request->getParsedBody();

        try {
            $this->validate($data);

            $customer = Customer::create($data);
            return $response->withJson($customer);
        } catch (InvalidArgumentException $e) {
            return $response->withJson(['error' => $e->getMessage()]);
        } catch (QueryException $e) {
            return $response->withJson(['error' => $e->getMessage()]);
        }
    }

    protected function validate($data){
        Assert::keyExists($data, 'cpf', 'cpf is required');
        Assert::notEmpty($data['cpf'], 'cpf is required');
        if(!validaCPF($data['cpf'])){
            throw new InvalidArgumentException('cpf invalido');
        }
        Assert::keyExists($data, 'name', 'name is required');
        Assert::notEmpty($data['name'], 'name is required');
        Assert::keyExists($data, 'email', 'email is required');
        Assert::notEmpty($data['email'], 'email is required');
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('email is invalid');
        } 
        
        if(Customer::where('email', $data['email'])->orWhere('cpf', $data['cpf'])->first()){
            throw new InvalidArgumentException('email or cpf already exists');
        }

        Assert::keyExists($data, 'password', 'password is required');
        Assert::notEmpty($data['password'], 'password is required');
    }
}
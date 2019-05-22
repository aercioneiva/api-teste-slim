<?php

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Models\Product;
use Webmozart\Assert\Assert;
use InvalidArgumentException;
use  Illuminate\Database\QueryException;

$app->group("/v1/products", function() use ($app) {

    $this->get("", function(Request $request, Response $response, $args = []) use ($app) {
       
        
        $products = Product::all();
        return $response->withJson($products);
    });

    $this->post("", function(Request $request, Response $response, $args = []) use ($app) {
        $data = $request->getParsedBody();

        try {
            Assert::keyExists($data, 'sku', 'sku is required');
            Assert::notEmpty($data['sku'], 'sku is required');
            Assert::keyExists($data, 'name', 'name is required');
            Assert::notEmpty($data['name'], 'name is required');
            Assert::keyExists($data, 'price', 'price is required');
            Assert::greaterThan($data['price'], 0, 'price must be greater than 0');
            Assert::float($data['price'], 'price must be float');

            if(Product::where('sku', $data['sku'])->first()){
                throw new InvalidArgumentException('sku already exists');
            }
            $product = Product::create($data);
            return $response->withJson($product);
        } catch (InvalidArgumentException $e) {
            return $response->withJson(['error' => $e->getMessage()]);
        } catch (QueryException $e) {
            return $response->withJson(['error' => $e->getMessage()]);
        }
    
        
    });

    
});


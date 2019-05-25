<?php
namespace App\Controllers;

use App\Models\Product;
use Webmozart\Assert\Assert;
use InvalidArgumentException;
use  Illuminate\Database\QueryException;

class ProductController{

    public function index($request, $response, $args) {
        $products = Product::all();
        return $response->withJson($products);
    }

    public function create($request, $response, $args){
        $data = $request->getParsedBody();

        try {
            $this->validate($data);

            $product = Product::create($data);
            return $response->withJson($product);
        } catch (InvalidArgumentException $e) {
            return $response->withJson(['error' => $e->getMessage()]);
        } catch (QueryException $e) {
            return $response->withJson(['error' => $e->getMessage()]);
        }
    }

    protected function validate($data){
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
    }
}
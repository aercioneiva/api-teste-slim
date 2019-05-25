<?php
namespace App\Controllers;

use App\Models\Order;
use App\Models\Item;
use App\Models\Customer;
use App\Models\Product;
use Webmozart\Assert\Assert;
use InvalidArgumentException;
use Illuminate\Database\QueryException;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController{

    public function index($request, $response, $args) {
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());
        
        $orders = Order::all();
       
        $resource = new Fractal\Resource\Collection($orders, function($order) {

            $items = [];

            foreach ($order->items as $item) {
                $item->product;

                $items[] = [
                    'amount' => $item->amount,
                    'price_unit' => $item->price_unit,
                    'total' => $item->total,
                    'product' =>[
                        'id' => $item->product->id,
                        'sku' => $item->product->sku,
                        'name' => $item->product->name
                    ]
                ];
            }

            return [
                'id'    => (int) $order->id,
                'status' => $order->status,
                'total'  => (float) $order->total,
                'created_at' => $order->created_at,
                'cancelDate' => $order->cancelDate ?? NULL,
                'buyer' => [
                    'id' => $order->customer->id,
                    'name' => $order->customer->name,
                    'cpf' => $order->customer->cpf,
                    'email' => $order->customer->email
                ],
                'items' => $items
            ];
        });

        return $response->withJson($manager->createData($resource)->toArray()['data']);
    }

    public function create($request, $response, $args){
        $id = $args["id"];
        $data = $request->getParsedBody();

        try {
            //customer
            if(empty($data['buyer']['id'])){
                throw new InvalidArgumentException('buyer[id] is required');
            }
            $customer = Customer::findOrFail($data['buyer']['id']);

            $this->validateCreate($data);
        } catch (InvalidArgumentException $e) {
            return $response->withJson(['error' => $e->getMessage()]);
        } catch (QueryException $e) {
            return $response->withJson(['error' => $e->getMessage()]);
        }catch (ModelNotFoundException $e) {
            return $response->withJson(['error' => 'order not found']);
        }

        //orders
        $order = new Order;
        $order->status = $data['status'];
        $order->total = $data['total'];
        $order->customer_id = $customer->id;
        $order->save();

        //items
        foreach($data['items'] as $it){
            $item = new Item;
            
            $item->amount = $it['amount'];
            $item->price_unit = $it['price_unit'];
            $item->total = $it['total'];
            $item->product_id = $it['product']['id'];
            $item->order_id = $order->id;
            $item->save();
        }

        return $response->withJson($order);
    }

    public function update($request, $response, $args){
        $id = $args["id"];
        $data = $request->getParsedBody();

        try {
            $this->validateUpdate($data);

            $order = Order::findOrFail($id);
            $order->status =  $data['status'];
            $order->cancelDate = date('Y-m-d H:i:s');
            $order->update();

            return $response->withJson($order);
        } catch (InvalidArgumentException $e) {
            return $response->withJson(['error' => $e->getMessage()]);
        } catch (QueryException $e) {
            return $response->withJson(['error' => $e->getMessage()]);
        } catch (ModelNotFoundException $e) {
            return $response->withJson(['error' => 'order not found']);
        }
    }

    protected function validateCreate($data){
        

        Assert::keyExists($data, 'status', 'status is required');
        Assert::notEmpty($data['status'], 'status is required');
        Assert::keyExists($data, 'total', 'total is required');
        Assert::greaterThan($data['total'], 0, 'total must be greater than 0');
        Assert::float($data['total'], 'total must be float');

        //items
        if(empty($data['items'])){
            throw new InvalidArgumentException('items is required');
        }
        Assert::isArray($data['items'], 'items must be array');
        foreach ($data['items'] as $item) {
            Assert::keyExists($item, 'amount', 'amount is required');
            Assert::greaterThan($item['amount'], 0, 'amount must be greater than 0');
            Assert::keyExists($item, 'price_unit', 'price_unit is required');
            Assert::greaterThan($item['price_unit'], 0, 'price_unit must be greater than 0');
            Assert::float($item['price_unit'], 'price_unit must be float');

            //product
            if(empty($item['product'])){
                throw new InvalidArgumentException('product is required');
            }
            Assert::isArray($item['product'], 'product must be object');
            Assert::keyExists($item['product'], 'id', 'id is required');
            Assert::notEmpty($item['product']['id'], 'id is required');
            Assert::greaterThan($item['product']['id'], 0, 'id must be greater than 0');
            Assert::keyExists($item['product'], 'sku', 'sku is required');
            Assert::notEmpty($item['product']['sku'], 'sku is required');
            Assert::numeric($item['product']['sku'], 'sku is not numeric');
            Assert::keyExists($item['product'], 'name', 'name is required');
            Assert::notEmpty($item['product']['name'], 'name is required');

            $product = Product::findOrFail($item['product']['id']);
        }
    }
    protected function validateUpdate($data){
        Assert::keyExists($data, 'status', 'status is required');
        Assert::notEmpty($data['status'], 'status is required');
        Assert::keyExists($data, 'order_id', 'order_id is required');
        Assert::notEmpty($data['order_id'], 'order_id is required');
    }
}
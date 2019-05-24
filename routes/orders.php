<?php


use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Models\Order;
use App\Models\Item;
use App\Models\Customer;
use Webmozart\Assert\Assert;
use InvalidArgumentException;
use  Illuminate\Database\QueryException;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;

$app->group("/v1/orders", function() use ($app) {

    $this->get("", function(Request $request, Response $response, $args = []) use ($app) {
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());
        
        $orders = Order::with(['items','customer'])->get();
       
        $resource = new Fractal\Resource\Collection($orders, function($order) {

          /* $items = new Fractal\Resource\Collection($order->items, function($item) {
                return [
                    'amount' => $item->amount,
                    'price_unit' => $item->price_unit,
                    'total'=>  $item->total
                ];
           });
           
            $items = [];

           foreach($order->items as $k => $item){
            $items[$k]['amount'] = $item->amount;
            $items[$k]['price_unit'] = $item->price_unit;
            $items[$k]['total'] = $item->total;
            $items[$k]['product'] = [
                'id' =>,
                'sku' =>,
                'title' =>
            ];
           }
*/
            return [
                'id'    => (int) $order->id,
                'status' => $order->status,
                'total'  => (float) $order->total,
                'created_at' => $order->created_at,
                'buyer' => [
                    'id' => $order->customer->id,
                    'name' => $order->customer->name,
                    'cpf' => $order->customer->cpf,
                    'email' => $order->customer->email
                ],
                'items' => $items
            ];
        });

        
        //$manager->createData($resource)->toArray()
        return $response->withJson($manager->createData($resource)->toArray());
    });

    $this->post("", function(Request $request, Response $response, $args = []) use ($app) {
        $data = $request->getParsedBody();
        
        //customer
        $customer = Customer::find($data['buyer']['id']);
        
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

        return $response->withJson($order::with(['items','customer'])->get());
    });

    
});


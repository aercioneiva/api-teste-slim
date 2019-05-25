<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model{
    protected $table = 'items';
    public $timestamps = false;
    protected $fillable = [
        'amount',
        'price_unit',
        'total',
        'order_id',
        'product_id'
    ];
    protected $hidden = [
        'product_id',
        'order_id'
    ];

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
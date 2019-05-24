<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model{
    protected $table = 'orders';
    public $timestamps = true;
    protected $fillable = [
        'status',
        'total',
        'customer_id'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }

}
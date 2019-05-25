<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model{
    protected $table = 'customers';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'cpf',
        'email'
    ];

    public function Orders()
    {
        return $this->hasMany('App\Models\Order');
    }
}
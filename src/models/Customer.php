<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model{
    protected $table = 'customers';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'cpf',
        'email',
        'password'
    ];

    protected $hidden = [
        'password'
    ];
    
    public function Orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function setPasswordAttribute($value){
        if(!empty($value)){
            $this->attributes['password'] = password_hash($value,PASSWORD_DEFAULT);
        }
    }
}
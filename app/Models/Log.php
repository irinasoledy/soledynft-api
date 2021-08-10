<?php

namespace App\Models;

use App\Base as Model;

class Log extends Model
{
    protected $fillable = [
                'user_id',
                'lang',
                'device',
                'currency',
                'country',
                'cart_id',
                'user_email',
                'problem',
                'file',
                'controller',
                'method',
                'referrer',
                'date',
            ];

    public function carts()
    {
        return $this->hasOne(Cart::class, 'id', 'cart_id');
    }
}

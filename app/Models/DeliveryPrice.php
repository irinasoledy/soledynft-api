<?php

namespace App\Models;

use App\Base as Model;

class DeliveryPrice extends Model
{
    protected $table = 'delivery_prices';

    protected $fillable = ['delivery_id', 'currency_id', 'price'];
}

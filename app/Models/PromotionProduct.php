<?php

namespace App\Models;

use App\Base as Model;

class PromotionProduct extends Model
{
    protected $table = 'promotions_products';

    protected $fillable = [
        'promotion_id',
        'product_id',
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function promotion()
    {
        return $this->hasOne(Promotion::class, 'id', 'promotion_id');
    }
}

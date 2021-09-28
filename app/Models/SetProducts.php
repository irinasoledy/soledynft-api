<?php

namespace App\Models;

use App\Base as Model;

class SetProducts extends Model
{
    protected $table = 'set_product';

    protected $fillable = [ 'set_id', 'product_id', 'subproduct_id', 'src', 'position', 'gift', 'display'];

    public function set()
    {
        return $this->hasOne(Set::class, 'id', 'set_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function items()
    {
        return $this->hasMany(Product::class, 'id', 'product_id');
    }
}

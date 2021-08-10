<?php

namespace App\Models;

use App\Base as Model;

class PromotionSet extends Model
{
    protected $table = 'promotions_sets';

    protected $fillable = [
        'promotion_id',
        'set_id',
    ];

    public function set()
    {
        return $this->hasOne(Set::class, 'id', 'set_id');
    }

    public function promotion()
    {
        return $this->hasOne(Promotion::class, 'id', 'promotion_id');
    }
}

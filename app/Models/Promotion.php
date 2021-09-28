<?php

namespace App\Models;

use App\Base as Model;

class Promotion extends Model
{
    protected $table = 'promotions';

    protected $fillable = [
        'alias',
        'code',
        'active',
        'on_home',
        'img',
        'img_mobile',
        'discount',
        'homewear',
        'bijoux',
        'type',
        'position',
    ];

    public function translations()
    {
        return $this->hasMany(PromotionTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(PromotionTranslation::class, 'promotion_id', 'id')->where('lang_id', self::$lang);
    }

    // public function products()
    // {
    //     return $this->hasMany(Product::class, 'promotion_id', 'id');
    // }

    public function translationByLang($lang)
    {
        return $this->hasOne(PromotionTranslation::class, 'promotion_id')->where('lang_id', $lang)->first();
    }

    // public function products()
    // {
    //     return $this->hasMany(PromotionProduct::class, 'promotion_id', 'id');
    // }

    public function products()
    {
        return $this->hasMany(Product::class, 'promotion_id', 'id');
    }

    public function sets()
    {
        return $this->hasMany(PromotionSet::class, 'promotion_id', 'id');
    }
}

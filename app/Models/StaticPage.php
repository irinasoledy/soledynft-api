<?php

namespace App\Models;

use App\Base as Model;

class StaticPage extends Model
{
    protected $table = 'static_pages';

    protected $fillable = ['alias', 'active'];

    public function translations()
    {
        return $this->hasMany(StaticPageTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(StaticPageTranslation::class, 'static_page_id')->where('lang_id', self::$lang);
    }
}

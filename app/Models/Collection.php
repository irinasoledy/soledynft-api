<?php
namespace App\Models;

use App\Base as Model;

class Collection extends Model
{
    protected $table = 'collections';

    protected $fillable = ['code', 'alias', 'banner', 'banner_mob', 'position', 'active', 'on_home', 'homewear', 'bijoux'];

    protected $appends = ['type'];

    public function getTypeAttribute()
    {
        if ($this->bijoux == 1) {
            return "bijoux";
        }else{
            return "homewear";
        }
    }

    public function translations()
    {
        return $this->hasMany(CollectionTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(CollectionTranslation::class , 'collection_id')->where('lang_id', self::$lang);
    }

    public function translationByLang($lang)
    {
        return $this->hasOne(CollectionTranslation::class, 'collection_id')->where('lang_id', $lang)->first();
    }

    public function sets()
    {
        return $this->hasMany(Set::class)->where('active', 1)->orderBy('position', 'asc');
    }

}

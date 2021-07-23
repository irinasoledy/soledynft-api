<?php

namespace App\Models;

use App\Base as Model;

class StaticPageTranslation extends Model
{
    protected $table = 'static_pages_translation';

    protected $fillable = [
                    'static_page_id',
                    'lang_id',
                    'name',
                    'body',
                    'meta_title',
                    'meta_description',
                    'meta_keywords',
                ];

    public function staticPage()
    {
        return $this->belongsTo(StaticPage::class);
    }
}

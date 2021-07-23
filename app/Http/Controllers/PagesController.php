<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Collection;
use App\Models\StaticPage;
use Session;

class PagesController extends Controller
{
    /***********************************  Render Methods ***********************
    *
    *  Render home page
    */
    public function index()
    {
        $promotions = Promotion::where('active', 1)->get();
        $seoData = $this->getPageByAlias('home');

        return view('front.home', compact('seoData', 'promotions'));
    }

    /**
     * Render the start page
     */
     public function general()
     {
         $seoData = $this->getPageByAlias('home');
         return view('front.general', compact('seoData'));
     }

    /**
     *  Render dynamic pages by alias
     */
    public function getPages($slug)
    {
        if (@$_COOKIE['country_id'] == 140) {
            if ($slug == "contacts") {
                $slug = "contacts-md";
            }
        }

        $seoData = $this->getPageByAlias($slug);

        $page = Page::where('alias', $slug)->first();
        $staticPage = StaticPage::where('alias', $slug)->first();

        if (!is_null($staticPage)) {
            $page = $staticPage;
            if (@$_COOKIE['country_id'] == 140) {
                $staticPage = StaticPage::where('alias', $slug.'_md')->first();
                if (!is_null($staticPage)) {
                    $page = $staticPage;
                }
            }
        }

        if (view()->exists('front/pages/'.$slug)) {
            return view('front.pages.'.$slug, compact('seoData', 'page'));
        }else{
            return view('front.pages.default', compact('seoData', 'page'));
        }
    }

    /**
     *  Render 404 page
     */
    public function get404()
    {
        return view('front.404');
    }

    /**
     * Get page data by alias
     */
    public function getPageByAlias($alias)
    {
        $page = Page::where('alias', $alias)->first();
        if (is_null($page)) abort(404);

        return $this->getSeo($page);
    }

    public function getOopsPage(Request $request)
    {
        Session::flash('disableForward', true);
        return view('front.dynamic.oops');
    }

    /**
     *  Get seo datas of pages
     */
    public function getSeo($page)
    {
        $seo['title'] = $page->translation($this->lang->id)->first()->meta_title ?? $page->translation($this->lang->id)->first()->title;
        $seo['keywords'] = $page->translation($this->lang->id)->first()->meta_keywords ?? $page->translation($this->lang->id)->first()->title;
        $seo['description'] = $page->translation($this->lang->id)->first()->meta_description ?? $page->translation($this->lang->id)->first()->title;

        return $seo;
    }
}

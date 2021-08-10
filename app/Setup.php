<?php

namespace App;

use App\Base as Model;
use App\Models\Country;
use App\Models\Lang;
use App\Models\Currency;
use App\Models\Warehouse;
use App\Models\Cart;
use App\Models\WishList;
use App\Models\ProductCategory;
use App\Models\Collection;
use App\Models\FrontUserUnlogged;
use App\Models\Module;
use View;

class Setup
{
    private $request;
    public static $setup = false;
    public static $userId;
    public static $unloggedUser;
    public static $site;
    public static $device;
    public static $lang;
    public static $langs;
    public static $country;
    public static $countries;
    public static $mainCurrency;
    public static $currency;
    public static $currencies;
    public static $warehouse;
    public static $seoData;

    public function __construct($request)
    {
        $this->request      = $request;
        self::$site         = $this->checkSiteType($this->request);
        self::$device       = $this->checkDevice();
        self::$userId       = $this->setUserId();
        self::$seoData      = $this->setSeoData();
        self::$countries    = Country::where('active', 1)->get();
        self::$mainCurrency = Currency::where('type', 1)->first();
        self::$currencies   = Currency::where('active', 1)->get();

        if (!@$_COOKIE['country_id'] && $request->method() == 'GET') {
            $this->checkUserData();
        }else{
            $this->setGeoData();
        }
if ($this->request->method() == 'GET') {
        $this->setLang($request);
}
        if(\Request::segment(1) == 'back'){
            View::share('menu', Module::where('parent_id', 0)->orderBy('position')->get());
        }
    }

    public function init()
    {
        if ($this->request->method() == 'GET') {
            $this->reinitForce();

            View::share("site", self::$site);
            View::share("device", self::$device);

            View::share("lang", self::$lang);
            View::share("langs", self::$langs);

            View::share("country", self::$country);
            View::share("countries", self::$countries);

            View::share("mainCurrency", self::$mainCurrency);
            View::share("currency", self::$currency);
            View::share("currencies", self::$currencies);
            View::share("warehouse", self::$warehouse);

            View::share("seoData", self::$seoData);
            View::share('unloggedUser', self::$unloggedUser);

            View::share('productList', json_encode([]));
            View::share('list', json_encode([]));

            Model::$lang = self::$lang->id;
            Model::$site = self::$site;
            Model::$currency = self::$currency->id;
            Model::$mainCurrency = self::$mainCurrency->id;
            Model::$warehouse = self::$warehouse->id;
            Model::$warehouseName = self::$warehouse->name;

            if ($this->request->method() == 'GET') {
                $this->shareCarts();
                $this->shareMenus();
            }
        }

    }

    public function reinitForce()
    {
        $country = Country::where('active', 1)->where('id', @$_COOKIE['country_id'])->first();
        if (is_null($country)) {
            $this->checkUserData();
            return redirect()->back();
        }
    }

    private function shareMenus()
    {
        // if (self::$site == 'homewear') {
            $categoriesMenuLoungewear = ProductCategory::where('parent_id', 0)->where('active', 1)->where('homewear', 1)->orderBy('position', 'asc')->get();
            $collectionsMenuLoungewear = Collection::where('active', 1)->orderBy('position', 'asc')->where('homewear', 1)->get();
            View::share('categoriesMenuLoungewear', $categoriesMenuLoungewear);
            View::share('collectionsMenuLoungewear', $collectionsMenuLoungewear);
        // }else{
            $categoriesMenuJewelry = ProductCategory::where('parent_id', 0)->where('active', 1)->where('bijoux', 1)->orderBy('position', 'asc')->get();
            $collectionsMenuJewelry = Collection::where('active', 1)->where('bijoux', 1)->orderBy('position', 'asc')->get();
            View::share('categoriesMenuJewelry', $categoriesMenuJewelry);
            View::share('collectionsMenuJewelry', $collectionsMenuJewelry);
        // }
    }

    private function shareCarts()
    {
        $cartProducts = Cart::with(['product.translation',
                                    'product.mainImage',
                                    'product.mainPrice'
                                ])
                                ->where('user_id', self::$userId)
                                ->where('parent_id', null)
                                ->where('active', 1)
                                ->orderBy('id', 'desc')
                                ->get();

        $wishProducts = WishList::with(['product.translation',
                                        'product.mainImage',
                                        'subproduct'
                                    ])
                                    ->where('user_id', self::$userId)
                                    ->get();

        $wishListIds = $wishProducts->pluck('product_id')->toArray();


        View::share('cartProducts', json_encode($cartProducts));
        View::share('wishProducts', json_encode($wishProducts));
        View::share('wishListIds', $wishListIds);
    }

    private function setSeoData()
    {
        $seo['title'] = env('SEO_TITLE');
        $seo['keywords'] = env('SEO_KEYWORDS');
        $seo['description'] = env('SEO_DESCRIPTION');

        return $seo;
    }

    private function setUserId()
    {
        if (\Cookie::has('user_id')) {
            $userId = \Cookie::get('user_id');
        }else{
            $userHash = md5(rand(0, 9999999).date('Ysmsd'));
            setcookie('user_id', $userHash, time() + 10000000, '/');
            $userId = $userHash;
        }

        self::$unloggedUser = FrontUserUnlogged::where('user_id', @$_COOKIE['user_id'])->first();
        return $userId;
    }

    private function setLang($request)
    {
        if ($request->segment(1)) {
            $lang = Lang::where('lang', $request->segment(1))->first();
            if (!is_null($lang))  self::$lang = $lang;
        }

        self::$langs = Lang::get();
        setcookie('lang_id', self::$lang->lang, time() + 10000000, '/');
        session(['applocale' => self::$lang->lang]);
        \App::setLocale(self::$lang->lang);
    }

    private function setGeoData()
    {
        self::$country = Country::where('id', @$_COOKIE['country_id'])->where('active', 1)->first();
        self::$lang = Lang::where('lang', @$_COOKIE['lang_id'])->first();
        self::$currency = Currency::where('id', @$_COOKIE['currency_id'])->first();
        self::$warehouse = Warehouse::where('id', @$_COOKIE['warehouse_id'])->first();
    }

    private function checkUserData()
    {
        try {
            $ip = @$_SERVER['HTTP_X_FORWARDED_FOR'];
            $details = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip={$ip}"));
            self::$country = Country::where('iso', $details->geoplugin_countryCode)->where('active', 1)->first();

            if (is_null(self::$country)) self::$country = Country::where('active', 1)->where('main', 1)->first();

            self::$lang       = self::$country->lang;
            self::$currency   = self::$country->currency;
            self::$warehouse  = self::$country->warehouse;
        } catch (\Exception $e) {
            self::$country    = Country::where('active', 1)->where('main', 1)->first();
            self::$lang       = self::$country->lang;
            self::$currency   = self::$country->currency;
            self::$warehouse  = self::$country->warehouse;
        }

        setcookie('country_id', self::$country->id, time() + 10000000, '/');
        setcookie('lang_id',    self::$lang->lang, time() + 10000000, '/');
        setcookie('currency_id', self::$currency->id, time() + 10000000, '/');
        setcookie('warehouse_id', self::$warehouse->id, time() + 10000000, '/');
    }

    private function checkSiteType($request)
    {
        if ($request->segment(2) == 'homewear') {
             return "homewear";
        }
        return "bijoux";
    }

    private function checkDevice()
    {
        // if (preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", @$_SERVER["HTTP_USER_AGENT"])) {
        //     return "mobile";
        // }
        return "mobile";
    }
}

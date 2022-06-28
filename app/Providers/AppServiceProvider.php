<?php

namespace App\Providers;

use App\Base as Model;
use App\Setup;
use Illuminate\Support\ServiceProvider;
use App\Models\FrontUserUnlogged;
use App\Models\Lang;
use App\Models\Module;
use App\Models\Cart;
use App\Models\Page;
use App\Models\WishList;
use App\Models\WishListSet;
use App\Models\ProductCategory;
use App\Models\Collection;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Set;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Entry;
use Illuminate\Http\Request;
use View;


class AppServiceProvider extends ServiceProvider
{
    public function boot(Request $request)
    {
        $setup = new Setup($request);
        $setup->init();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }


    public function boot_(Request $request)
    {
        $this->app['request']->server->set('HTTPS', true);
        $this->checkDevice($request);

        $notShippingCounrty = false;
        $ip = @$_SERVER['HTTP_X_FORWARDED_FOR'];

        $userCountry = Country::where('active', 1)->where('main', 1)->first();
        $initWareHouse = @$_COOKIE['warehouse_id'];
        $currency = false;

        if (!@$_COOKIE['country_id'] && $request->method() == 'GET') {
            try {
                $details = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip={$ip}"));

                $userCountry = Country::where('iso', $details->geoplugin_countryCode)->where('active', 1)->first();

                if (is_null($userCountry)) {
                    $userCountry = Country::where('active', 1)->where('main', 1)->first();
                    $notShippingCounrty = true;
                } else {
                    $notShippingCounrty = false;
                }

                setcookie('country_id', $userCountry->id, time() + 10000000, '/');
                setcookie('lang_id', $userCountry->lang->lang, time() + 10000000, '/');
                setcookie('currency_id', $userCountry->currency_id, time() + 10000000, '/');

                $currency = Currency::where('id', $userCountry->currency_id)->first();
            } catch (\Exception $e) {
                setcookie('country_id', $userCountry->id ?? 1, time() + 10000000, '/');
                setcookie('lang_id', $userCountry->lang->lang ?? 1, time() + 10000000, '/');
                setcookie('currency_id', $userCountry->currency_id, time() + 10000000, '/');

                $currency = Currency::where('id', $userCountry->currency_id)->first();
            }

            if (!@$_COOKIE['warehouse_id']) {
                setcookie('warehouse_id', $userCountry->warehouse_id ?? 1, time() + 10000000, '/');
                $initWareHouse = $userCountry->warehouse_id;
            }
        }

        if (@$_COOKIE['lang_id']) {
            $lang = Lang::where('lang', @$_COOKIE['lang_id'])->first();
            if (is_null($lang)) {
                $lang = Lang::where('default', '1')->first();
            }
        } else {
            $lang = Lang::where('lang', $userCountry->lang->lang ?? 'ro')->first();
        }

        if (\Request::segment(2) == 'homewear') {
            $siteType = 'homewear';
        } elseif (\Request::segment(2) == 'bijoux') {
            $siteType = 'bijoux';
        } else {
            $siteType = 'bijoux';
        }

        if (!is_null($lang)) {
            if (is_null($userCountry)) {
                $userCountry = Country::first();
            }

            $currentLang = Lang::where('lang', \Request::segment(1))->first()->lang ?? $lang->lang;
            session(['applocale' => $currentLang]);
            \App::setLocale($currentLang);

            $lang = Lang::where('lang', session('applocale'))->first();

            $country = Country::where('id', @$_COOKIE['country_id'] ?? $userCountry->id)->first();

            $mainCurrency = Currency::where('type', 1)->first();

            if ($currency == false) {
                $currency = Currency::where('id', @$_COOKIE['currency_id'])->first() ?? $mainCurrency;
            }

            $mainWarehouse = Warehouse::where('default', 1)->first();
            $warehouse = Warehouse::where('id', $initWareHouse)->first() ?? $mainWarehouse;

            // Currency and Prices form Moldova
            // if ($country->iso == 'MD') {
            //     // $mainCurrency = Currency::where('id', $country->currency_id)->first();
            //     // $currency = $mainCurrency;
            //     $currency = Currency::where('id', $country->currency_id)->first();
            // }

            $unloggedUser = FrontUserUnlogged::where('user_id', @$_COOKIE['user_id'])->first();

            $seo['title'] = env('SEO_TITLE');
            $seo['keywords'] = env('SEO_KEYWORDS');
            $seo['description'] = env('SEO_DESCRIPTION');

            $this->setUserId();

            if ($request->method() == 'GET') {
                if (\Request::segment(1) == 'back') {
                    View::share('menu', Module::where('parent_id', 0)->orderBy('position')->get());
                } else {
                    if ($siteType == 'homewear') {
                        $categoriesMenuLoungewear = ProductCategory::where('parent_id', 0)->where('active', 1)->where('homewear', 1)->orderBy('position', 'asc')->get();
                        $collectionsMenuLoungewear = Collection::where('active', 1)->orderBy('position', 'asc')->where('homewear', 1)->get();
                        View::share('categoriesMenuLoungewear', $categoriesMenuLoungewear);
                        View::share('collectionsMenuLoungewear', $collectionsMenuLoungewear);
                    } else {
                        $categoriesMenuJewelry = ProductCategory::where('parent_id', 0)->where('active', 1)->where('bijoux', 1)->orderBy('position', 'asc')->get();
                        $collectionsMenuJewelry = Collection::where('active', 1)->where('bijoux', 1)->orderBy('position', 'asc')->get();
                        View::share('categoriesMenuJewelry', $categoriesMenuJewelry);
                        View::share('collectionsMenuJewelry', $collectionsMenuJewelry);
                    }

                    $this->shareCarts();
                }

                View::share('site', $siteType);
                View::share('langs', Lang::orderBy('id', 'asc')->get());
                View::share('lang', $lang);
                View::share('countries', Country::orderBy('id', 'asc')->where('active', 1)->get());
                View::share('country', $country);
                View::share('currencies', Currency::orderBy('id', 'asc')->get());
                View::share('currency', !is_null($currency) ? $currency : $mainCurrency);
                View::share('warehouse', !is_null($warehouse) ? $warehouse : $mainWarehouse);
                View::share('mainCurrency', $mainCurrency);
                View::share('seoData', $seo);
                View::share('unloggedUser', $unloggedUser);
                View::share('notShippingCounrty', $notShippingCounrty);
                View::share('productList', json_encode([]));
                View::share('list', json_encode([]));
            }


            Model::$lang = $lang->id;
            Model::$site = $siteType;
            Model::$currency = $currency->id;
            Model::$mainCurrency = $mainCurrency->id;
            Model::$warehouse = $warehouse->id;
            Model::$warehouseName = $warehouse->name;

        } else {
            exit('language is not exists!');
        }
    }
}

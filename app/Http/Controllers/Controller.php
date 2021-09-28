<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Lang;
use App\Models\Country;
use App\Models\Currency;
use App\Base as Model;
use App\Setup;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $langs;
    protected $lang;
    protected $siteType;
    protected $device;
    protected $warehouse;
    protected $mainCurrency;
    protected $currency;
    protected $country;
    protected $userId;

    public function __construct()
    {
        $this->langs = Setup::$langs;
        $this->lang = Setup::$lang;
        $this->siteType = Setup::$site;
        $this->device = Setup::$device;
        // $this->warehouse = Setup::$warehouse->name;
        $this->mainCurrency = Setup::$mainCurrency;
        $this->currency = Setup::$currency;
        $this->country = Setup::$country;
        $this->userId = Setup::$userId;


        $this->lang = Lang::where('default', 1)->first();
        $this->langs = Lang::get();
    }

    public function saveSettings(Request $request)
    {
        $url = str_replace(url('/'), '', url()->previous());
        $previousLang = $this->lang->lang;

        $country = Country::where('id', $request->get('countryId'))->first();
        $lang   = Lang::where('id', $request->get('langId'))->first();
        $currency = Currency::where('id', $request->get('currencyId'))->first();

        if (!is_null($country)) {
            setcookie('country_id', $country->id, time() + 10000000, '/');
            setcookie('currency_id', $currency->id, time() + 10000000, '/');
        }

        if (!is_null($lang)) {
            setcookie('lang_id', $lang->lang, time() + 10000000, '/');
            $url =  str_replace('/'.$previousLang, '/'.$lang->lang, $url);
        }

        return url($url);
    }

    public function getCurrency(Request $request)
    {
        $currency = Currency::where('id', $request->get('currencyId'))->first();

        if (!is_null($currency)) {
            return $currency;
        }
        return "false";
    }

    public function getCountry(Request $request)
    {
        $country = Country::where('id', $request->get('countryId'))->first();

        if (!is_null($country)) {
            return $country;
        }
        return "false";
    }

    public function saveCountryUser(Request $request)
    {
        $country = Country::where('id', $request->get('countryId'))->first();
        $lang = Lang::where('id', $request->get('langId'))->first();
        $currency = Currency::where('id', $request->get('currencyId'))->first();

        if (!is_null($country)) {
            setcookie('country_id', $country->id, time() + 10000000, '/');
            setcookie('lang_id', $lang->lang, time() + 10000000, '/');
            setcookie('currency_id', $currency->id, time() + 10000000, '/');
        }

        return $lang->lang;
    }

    public function acceptCookiePolicy()
    {
        setcookie('cookie_policy', true, time() + 10000000, '/');
    }

    public function setUserSettings(Request $request)
    {
        $url = str_replace(url('/'), '', url()->previous());
        $previousLang = $this->lang->lang;

        $country = Country::where('id', $request->get('country_id'))->first();
        $lang = Lang::where('id', $request->get('lang_id'))->first();
        $currency = Currency::where('id', $request->get('currency_id'))->first();

        if (!is_null($country)) {
            setcookie('country_id', $country->id, time() + 10000000, '/');
            setcookie('warehouse_id', $country->warehouse_id, time() + 10000000, '/');
        }

        if (!is_null($lang)) {
            setcookie('lang_id', $lang->lang, time() + 10000000, '/');
            $url =  str_replace('/'.$previousLang, '/'.$lang->lang, $url);
        }

        if ($country->iso == "MD") {
            if (!is_null($currency)) {
                setcookie('currency_id', $country->currency_id, time() + 10000000, '/');
            }
        }else{
            if (!is_null($currency)) {
                if ($currency->abbr == "MDL") {
                    $currency = Currency::where('type', 1)->first();
                    setcookie('currency_id', $currency->id, time() + 10000000, '/');
                }else{
                    setcookie('currency_id', $currency->id, time() + 10000000, '/');
                }
            }
        }
        return redirect($url);
    }
}

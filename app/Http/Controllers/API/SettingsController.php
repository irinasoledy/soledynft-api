<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Models\Lang;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Banner;
use App\Models\StaticPage;



class SettingsController extends ApiController
{
    public function getSettings()
    {
        $data['langs'] = Lang::where('active', 1)->get();
        $data['countries'] = Country::where('active', 1)->orderBy('main', 'desc')->get();
        $data['currencies'] = Currency::where('active', 1)->orderBy('type', 'desc')->get();

        return $this->respond($data);
    }

    public function getTranslations(Request $request)
    {
        $this->swithLang($request->get('lang'));

        $strings = \Cache::rememberForever('lang.js', function () use ($request){
            $lang = $request->get('lang');

            $files   = glob(resource_path('lang/' . $lang . '/vars.php'));
            $strings = [];

            foreach ($files as $file) {
                $name           = basename($file, '.php');
                $strings[$name] = require $file;
            }

            return $strings;
        });

        return $this->respond($strings);
    }

    public function getBanners(Request $request)
    {
        $this->swithLang($request->get('lang'));

        $banners = Banner::get();

        return $this->respond($banners);
    }

    public function getStaticPages(Request $request)
    {
        $this->swithLang($request->get('lang'));

        $pages = StaticPage::with(['translation'])->get();

        return $this->respond($pages);
    }
}

<?php

namespace App\Http\Controllers\Admin;
use App\Base as Model;
use App\Http\Controllers\Controller;
use Admin\Http\Controllers\AutoUploadController;
use Admin\Http\Controllers\CurrenciesController;
use Admin\Http\Controllers\AutoMetaScriptsController;
use Admin\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Warehouse;
use App\Models\WarehousesStock;
use App\Models\Parameter;
use App\Models\ParameterValue;
use App\Models\ParameterValueProduct;
use App\Models\Promotion;
use App\Models\ProductPrice;
use App\Models\Currency;
use App\Models\SubProduct;
use App\Models\Country;
use App\Models\Lang;
use App\Models\ProductImage;
use App\Models\ProductImageTranslation;
use App\Models\AutometaScript;
use App\Models\TranslationGroup;
use App\Models\Translation;
use App\Models\TranslationLine;
use App\Models\Collection;
use App\Models\Set;
use App\Models\SetProducts;
use App\Models\PromotionProduct;
use App\Models\PromotionSet;
use App\Models\Brand;
use GuzzleHttp\Client;
use Revolution\Google\Sheets\Facades\Sheets;
use Edujugon\GoogleAds\GoogleAds;
use Google\AdsApi\AdWords\v201809\cm\CampaignService;

use MOIREI\GoogleMerchantApi\Facades\ProductApi;
use MOIREI\GoogleMerchantApi\Facades\OrderApi;

use Session;

class GoogleController extends Controller
{
    public $issetProducts;

    public function googleAdsMain()
    {
        $ads = new GoogleAds();

        $ads->env('test')
            ->oAuth([
                'clientId'      => '123545481940-4mdaf48bm17k5bteu5agvn9hu93rikkj.apps.googleusercontent.com',
                'clientSecret'  => 'VhUXtjChcGC3Ogz1xQjWXf_3',
                'refreshToken'  => '1//096yXDMsnzqYaCgYIARAAGAkSNwF-L9IrgMpQqFm3BFwo2NGmlOMGrtyPtjp64nOxiLhR2C9K6YjkajEKmmRPQ_t8feST0m7dGUA'
            ])
            ->session([
                'developerToken'    => 'HGnirrdw3jWZLYhC48Tl5g',
                'clientCustomerId'  => '940-653-7294'
            ]);

        // dd($ads);

        $ads->service(CampaignService::class)
                ->select(['Id', 'Name', 'Status', 'ServingStatus', 'StartDate', 'EndDate'])
                ->get();

        dd($ads);

        // $ads->oAuth([
        //     'clientId' => '105506236289725069958',
        //     'clientSecret' => 'test',
        //     'refreshToken' => 'TEST'
        //
        // ]);
    }

    public function googleApiContent()
    {
        $countries = Country::where('active', 1)->get();
        $currencies = Currency::get();

        return view('admin.google.apiContent.index', compact('countries', 'currencies'));
    }

    public function insertNewContent(Request $request)
    {
        $data = 'Content';
        $view = view('admin.google.progressBar', compact('data'));
        echo $view->render();

        $siteTypes = ['bijoux', 'homewear'];
        $lang = Lang::where('lang', $request->get('lang'))->first();

        // foreach ($siteTypes as $key => $siteType) {

            // $products = Product::where($siteType, 1)->inRandomOrder()->get();
            $siteType = $request->get('type');
            $products = Product::where($request->get('type'), 1)->inRandomOrder()->get();
            $currency = Currency::find($request->get('currency_id'));

            $details['products'] = $products;
            $details['siteType'] = $siteType;
            $request['request'] = $request;


            $prods = ProductApi::get()->then(function($data) use ($details, $currency, $lang, $request){});

            dd($prods);

            ProductApi::get()->then(function($data) use ($details, $currency, $lang, $request){
                $issetProducts = [];
                if (array_key_exists('resources', $data)) {
                    foreach ($data['resources'] as $key => $item) {
                        $issetProducts[$item['offerId']] = $item['offerId'];
                    }

                    foreach ($details['products'] as $key => $product) {
                        // echo $product->id .'<br>';

                        if (!array_key_exists($product->code, $issetProducts)) {
                            try {

                                $translation = $product->translationByLang($lang->id);

                                $attributes = [
                                    'id'        => $product->code,
                                    'title'     => $translation->name,
                                    'description' => $translation->atributes ?? 'lorem Ipsum',
                                    'condition' => 'new',
                                    'availability' => 'in stock',
                                    'image_link' => 'https://annepopova.com/images/products/fbq/'.$product->googleImage->src,
                                    'gtin'      => $product->ean_code,
                                    'mpn'       => $product->ean_code,
                                    'google_product_category' => $product->category->translationByLang($lang->id)->name,
                                    'product_type' => $product->category->translationByLang($lang->id)->name,
                                    'Gender'    => 'female',
                                    'Color'     => 'Brick-red',
                                    'price'     => $product->priceByID($request->get('currency_id'))->first()->price,
                                    'link'      => 'https://annepopova.com/'. $request->get('lang') .'/'. $details['siteType'] .'/catalog/'.$product->category->alias.'/'.$product->alias,
                                    'targetCountry' => $request->get('country'),
                                    'contentLanguage' => $request->get('lang')
                                ];

                                // dd($translation);
                                // echo $product->id .'<br>';
                                $p = ProductApi::insert(function($product) use($attributes, $currency){
                                    $product->with($attributes)
                                            ->title($attributes['title'])
                                            ->description($attributes['description'])
                                            ->targetCountry($attributes['targetCountry'])
                                            ->contentLanguage($attributes['contentLanguage'])
                                            ->link($attributes['link'])
                                        	->image($attributes['image_link'])
                                        	->price($attributes['price'], $currency->abbr)
                                            ->gtin($attributes['gtin'])
                                            ->mpn($attributes['mpn']);
                                })->then(function($data){
                                    echo '<small>Inserted '. $data['offerId']. '</small><br>';
                                })->otherwise(function(){
                                    echo 'Insert failed';
                                })->catch(function($e){
                                    dump($e);
                                });
                            } catch (\Exception $e) {
                                // echo 'Erorr: ' .$product->code;
                            }
                        }
                    }
                }
            });

            ProductApi::get()->then(function($data) use ($details){
                // $issetProducts = [];

                dd($data);
                // if (array_key_exists('resources', $data)) {
                //     foreach ($data['resources'] as $key => $item) {
                //         $issetProducts[] = $item['offerId'];
                //     }
                //
                //     foreach ($details['products'] as $key => $product) {
                //         echo $product->id .'<br>';
                //         if (!in_array($issetProducts, $product->code)) {
                //             $translation = $product->translationByLang($lang->id);
                //             $attributes = [
                //                 'id'        => $product->code,
                //                 'title'     => $translation->name,
                //                 'description' => $translation->atributes ?? 'lorem Ipsum',
                //                 'condition' => 'new',
                //                 'availability' => 'in stock',
                //                 'image_link' => 'https://annepopova.com/images/products/fbq/'.$product->googleImage->src,
                //                 'gtin'      => $product->ean_code,
                //                 'mpn'       => $product->ean_code,
                //                 'google_product_category' => $product->category->translationByLang($lang->id)->name,
                //                 'product_type' => $product->category->translationByLang($lang->id)->name,
                //                 'Gender'    => 'female',
                //                 'Color'     => 'Brick-red',
                //                 'price'     => $product->priceByID($request->get('currency_id'))->first()->price,
                //                 'link'      => 'https://annepopova.com/'. $request->get('lang') .'/'. $details['siteType'] .'/catalog/'.$product->category->alias.'/'.$product->alias,
                //                 'targetCountry' => $request->get('country'),
                //                 'contentLanguage' => $request->get('lang')
                //             ];
                //
                //             $p = ProductApi::insert(function($product) use($attributes, $currency){
                //                 $product->with($attributes)
                //                         ->title($attributes['title'])
                //                         ->description($attributes['description'])
                //                         ->targetCountry($attributes['targetCountry'])
                //                         ->contentLanguage($attributes['contentLanguage'])
                //                         ->link($attributes['link'])
                //                     	->image($attributes['image_link'])
                //                     	->price($attributes['price'], $currency->abbr)
                //                         ->gtin($attributes['gtin'])
                //                         ->mpn($attributes['mpn']);
                //             })->then(function($data){
                //                 echo '<small>Inserted '. $data['offerId']. '</small><br>';
                //             })->otherwise(function(){
                //                 echo 'Insert failed';
                //             })->catch(function($e){
                //                 dump($e);
                //             });
                //         }
                //     }
                //     // dd($issetProducts);
                // }
            });
        // }
    }

    public function insertGoogleMerchant($siteType, $lang, $currency, $country, $products = null, $prodIds = [])
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 900);

        $currentLang = Lang::where('lang', $lang)->first();
        $currentCurrency = Currency::where('abbr', $currency)->first();


        if (count($prodIds) == 0) {
            if (Session::has('prodIds')) {
                $prodIds = Session::get('prodIds');
            }else{
                $prodIds = [];
            }
        }


        if (is_null($products)) {
            $products = Product::whereNotIn('id', $prodIds)->where($siteType, 1)->where('active', 1)->limit(3)->get();
        }

        $insertedProducts = array_merge($prodIds, $products->pluck('id')->toArray());

        if ($products->count() > 0) {
            foreach ($products as $key => $product) {
                $translation = $product->translationByLang($currentLang->id);

                if (!is_null($product->googleImage)) {
                    $imageLink = 'https://annepopova.com/images/products/fbq/'. $product->googleImage->src;
                }else{
                    $imageLink = 'https://annepopova.com/images/products/og/'. $product->mainImage->src;
                }

                $attributes = [
                    'id'        => $product->code,
                    'title'     => $translation->name,
                    'description' => $translation->atributes ?? 'lorem Ipsum',
                    'condition' => 'new',
                    'availability' => 'in stock',
                    'image_link' => $imageLink ,
                    'gtin'      => $product->ean_code ?? $product->code,
                    'mpn'       => $product->ean_code ?? $product->code,
                    'google_product_category' => $product->category->translationByLang($currentLang->id)->name,
                    'product_type' => $product->category->translationByLang($currentLang->id)->name,
                    'Gender'    => 'female',
                    'Color'     => 'Brick-red',
                    'price'     => $product->priceByID($currentCurrency->id)->first()->price,
                    'link'      => 'https://annepopova.com/'. $lang .'/'. $siteType .'/catalog/'.$product->category->alias.'/'.$product->alias,
                    'targetCountry' => $country,
                    'contentLanguage' => $lang
                ];

                ProductApi::insert(function($product) use($attributes, $currency){
                    $product->with($attributes)
                            ->title($attributes['title'])
                            ->description($attributes['description'])
                            ->targetCountry($attributes['targetCountry'])
                            ->contentLanguage($attributes['contentLanguage'])
                            ->link($attributes['link'])
                            ->image($attributes['image_link'])
                            ->price($attributes['price'], $currency)
                            ->gtin($attributes['gtin'])
                            ->mpn($attributes['mpn']);
                    })->then(function($data){
                        // echo '<small>Inserted '. $data['offerId']. '</small><br>';
                    })->otherwise(function(){
                        echo 'Insert failed';
                    })->catch(function($e){
                        dump($e);
                    });

                if ($key == $products->count() - 1 ) {
                    Session::flash('prodIds', $insertedProducts);
                    // print_r($insertedProducts);

                    $view = view('admin.google.progressBarMercant', compact('siteType', 'currentLang', 'currency', 'country', 'insertedProducts', 'products'));
                    echo $view->render();
                    return;

                    // return view('admin.google.progressBarMercant', compact('siteType', 'lang', 'currency', 'country'));
                    // return redirect('/back/google-api-content/recursive-insert/'. $siteType .'/'. $lang .'/'. $currency .'/'. $country);
                }

            }
        }
    }

    // Post insert content
    public function insertContent(Request $request)
    {
        $siteType = $request->get('type');
        $lang = $request->get('lang');
        $currency = Currency::find($request->get('currency_id'));
        $country = $request->get('country');
        $products = Product::where('active', 1)->limit(5)->get();
        $prodIds = [];

        $this->insertGoogleMerchant($siteType, $lang, $currency->abbr, $country, $products, $prodIds);
        return;

        dd('vsdfkm');
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 900);

        $page = $request->get('page') ?? 0;
        $data = 'Content';
        $view = view('admin.google.progressBar', compact('data'));
        echo $view->render();

        $lang = Lang::where('lang', $request->get('lang'))->first();
        $siteType = $request->get('type');

        try {
            $products = Product::where($request->get('type'), 1)->inRandomOrder()->get();
            $currency = Currency::find($request->get('currency_id'));
                foreach ($products as $key => $product) {
                    $translation = $product->translationByLang($lang->id);
                    if (!is_null($product->googleImage)) {
                        $imageLink = 'https://annepopova.com/images/products/fbq/'. $product->googleImage->src;
                    }else{
                        $imageLink = 'https://annepopova.com/images/products/og/'. $product->mainImage->src;
                    }
                    $attributes[] = [
                        'id'        => $product->code,
                        'title'     => $translation->name,
                        'description' => $translation->atributes ?? 'lorem Ipsum',
                        'condition' => 'new',
                        'availability' => 'in stock',
                        'image_link' => $imageLink ,
                        'gtin'      => $product->ean_code ?? $product->code,
                        'mpn'       => $product->ean_code ?? $product->code,
                        'google_product_category' => $product->category->translationByLang($lang->id)->name,
                        'product_type' => $product->category->translationByLang($lang->id)->name,
                        'Gender'    => 'female',
                        'Color'     => 'Brick-red',
                        'price'     => $product->priceByID($request->get('currency_id'))->first()->price,
                        'link'      => 'https://annepopova.com/'. $request->get('lang') .'/'. $siteType .'/catalog/'.$product->category->alias.'/'.$product->alias,
                        'targetCountry' => $request->get('country'),
                        'contentLanguage' => $request->get('lang')
                    ];
                }

                $p = ProductApi::insert(function($product) use($attributes, $currency){
                    foreach ($attributes as $key => $attribute) {
                        $product->with($attribute)
                                ->title($attribute['title'])
                                ->description($attribute['description'])
                                ->targetCountry($attribute['targetCountry'])
                                ->contentLanguage($attribute['contentLanguage'])
                                ->link($attribute['link'])
                            	->image($attribute['image_link'])
                            	->price($attribute['price'], $currency->abbr)
                                ->gtin($attribute['gtin'])
                                ->mpn($attribute['mpn']);
                                echo $key + 1 .' - ';
                                echo $attribute['title'].'<br>';
                        }
                })->then(function($data){
                    echo '<small>Inserted '. $data['offerId']. '</small><br>';
                })->otherwise(function(){
                    echo 'Insert failed';
                })->catch(function($e){
                    dump($e);
                });

                    // if ($key == $products->count() - 1) {
                    //     dd($request->fullUrl(). '&page='. $page + 1);
                    //     return redirect($request->fullUrl().'&page='.$page + 1);
                    // }
        } catch (\Exception $e) {
            dd($e);
            dd('error: ' . $translation->name, $product->googleImage, $product->priceByID($request->get('currency_id'))->first(), $product->ean_code, $attributes);
        }
    }

    public function googleMerchantApi()
    {
        // $product = Product::where('bijoux', 1)->inRandomOrder()->find(41);
        //
        // $translation = $product->translationByLang(38);
        // $currency = Currency::find($request->get('currency_id'));
        //
        // $attributes = [
        //     'id' => $product->code,
        //     'title' => $translation->name,
        //     'description' => $translation->atributes,
        //     'condition' => 'new',
        //     'availability' => 'in stock',
        //     'imagelink' => 'https://annepopova.com/images/products/og/'.$product->mainImage->src,
        //     'gtin' => $product->ean_code,
        //     'mpn' => $product->ean_code,
        //     'google_product_category' => $product->category->translationByLang(38)->name,
        //     'product_type' => $product->category->translationByLang(38)->name,
        //     'Gender' => 'female',
        //     'Color' => 'Brick-red',
        //     'price' => $product->priceByID($request->get('currency_id'))->first()->price,
        //     'link' => 'https://annepopova.com/ro'. $details['siteType'] .'catalog/'.$product->alias,
        // ];
        //
        // $p = ProductApi::insert(function($product) use($attributes, $currency){
        //     $product->with($attributes)
        //         	->link($attributes['link'])
        //         	->price($attributes['price'], $currency->abbr);
        // })->then(function($data){
        //     dd($data);
        //     echo 'Product inserted';
        // })->otherwise(function(){
        //     echo 'Insert failed';
        // })->catch(function($e){
        //     dump($e);
        // });
        //
        // dd($p);
    }

    public function index()
    {
        return view('admin.google.index');
    }

    public function getCategoriesId()
    {
        $categories = ProductCategory::orderBy('position', 'asc')->get();
        return view('admin.google.categoriesIdList', compact('categories'));
    }

    public function getTransData()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 900);
        $translationGroups = TranslationGroup::get();
        return view('admin.google.transData', compact('translationGroups'));
    }

    public function getBrands()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 900);
        $brands = Brand::get();
        return view('admin.google.brands', compact('brands'));
    }

    public function setSiteType($siteType)
    {
        $data['homewear'] = 0;
        $data['bijoux'] = 0;

        if ($siteType == 'homewear') { $data['homewear'] = 1; }
        if ($siteType == 'bijoux') { $data['bijoux'] = 1; }

        return $data;
    }

    public function uploadProducts()
    {
        $data = 'Products';
        $view = view('admin.google.progressBar', compact('data'));
        echo $view->render();

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                        // ->sheetList();
                        // ->sheetById(config('sheets.post_sheet_id'))
                       ->sheetById(51890818)
                       ->all();


        $sheets = $this->parseSheet($sheets);
        $productsID = [];

        if (!empty($sheets)) {
            foreach ($sheets as $key => $item) {

                $siteType = $this->setSiteType($item['Type']);
                $checkProduct = Product::where('alias', $item['alias'])->where('code', $item['code_prod'])->first();

                if (is_null($checkProduct)) {
                    $product = Product::create([
                        'category_id'   => $item['category_id'],
                        'alias'         => $item['alias'],
                        'position'      => $item['position'],
                        'code'          => $item['code_prod'],
                        'homewear'      => $siteType['homewear'],
                        'bijoux'        => $siteType['bijoux'],
                        'active'        => $item['Active'],
                        'brand_id'      => $item['Brand'],
                        'w_b'           => $item['W&B'],
                        'amazon'        => $item['Amazon'],
                        'ozon'          => $item['Ozon'],
                    ]);

                    foreach ($this->langs as $key => $oneLang) {
                        $product->translation()->create([
                            'lang_id'   => $oneLang->id,
                            'name'      => $item['prodName_'.$oneLang->lang],
                            'atributes' => $item['Attributes_'.$oneLang->lang],
                            'info' => $item['care_sizing_'.$oneLang->lang],
                        ]);
                    }

                    $productsID[] = $product->id;
                }else{
                    $checkProduct->update([
                        'category_id'   => $item['category_id'],
                        'alias'         => $item['alias'],
                        'position'      => $item['position'],
                        'code'          => $item['code_prod'],
                        'homewear'      => $siteType['homewear'],
                        'bijoux'        => $siteType['bijoux'],
                        'active'        => $item['Active'],
                        'brand_id'      => $item['Brand'],
                        'w_b'           => $item['W&B'],
                        'amazon'        => $item['Amazon'],
                        'ozon'          => $item['Ozon'],
                    ]);

                    $checkProduct->translations()->delete();

                    foreach ($this->langs as $key => $oneLang) {
                        $checkProduct->translation()->create([
                            'lang_id'   => $oneLang->id,
                            'name'      => $item['prodName_'.$oneLang->lang],
                            'atributes' => $item['Attributes_'.$oneLang->lang],
                            'info'      => $item['care_sizing_'.$oneLang->lang],
                        ]);
                    }
                }
            }
        }

        $products = Product::whereIn('id', $productsID)->get();

        $script = AutometaScript::find(1);
        if (!is_null($script)) {
            $productAll = Product::get();
            $autometa = new AutoMetaScriptsController();
            $autometa->setScriptsToProducts($productAll, $script, 'only_empty');
        }

        $autoupload = new AutoUploadController();

        foreach ($products as $key => $product) {
            // generate subproducts
            $autoupload->generateSubprodusesForProduct($product);

            // generate prices
            $autoupload->generatePrices($product);

            $warehouses = Warehouse::get();
            // generate stocks
            if ($product->subproducts) {
                foreach ($product->subproducts as $key => $subproduct) {
                    foreach ($warehouses as $key => $warehouse) {
                        WarehousesStock::create([
                            'warehouse_id' => $warehouse->id,
                            'product_id' => $product->id,
                            'subproduct_id' => $subproduct->id,
                            'stock' => 0,
                        ]);
                    }
                }
            }else{
                foreach ($warehouses as $key => $warehouse) {
                    WarehousesStock::create([
                        'warehouse_id' => $warehouse->id,
                        'product_id' => $product->id,
                        'subproduct_id' => null,
                        'stock' => 0,
                    ]);
                }
            }
        }

        $admin = new AdminController();
        $admin->checkProductsStocks();
    }

    public function uploadParameters()
    {
        $data = 'Parameters';
        $view = view('admin.google.progressBar', compact('data'));
        echo $view->render();

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(1485788677)
                       ->all();

        $sheets = $this->parseSheet($sheets);

        foreach ($sheets as $key => $items) {
            foreach ($items as $key => $item) {
                $parameter = Parameter::where('key', $key)->first();
                if (!is_null($parameter)) {
                    if ($parameter->type !== 'text') {

                    $parameterProductValue = ParameterValueProduct::where('product_id', $items['Prod_ID'])->where('parameter_id', $parameter->id)->first();

                    if (is_null($parameterProductValue)) {
                         ParameterValueProduct::create([
                             'product_id' => $items['Prod_ID'],
                             'parameter_id' => $parameter->id,
                             'parameter_value_id' => $item,
                         ]);
                    }else{
                        $parameterProductValue->update([
                            'parameter_value_id' => $item,
                        ]);
                    }
                }else{
                    $parameterProductValue = ParameterValueProduct::where('product_id', $items['Prod_ID'])->where('parameter_id', $parameter->id)->first();

                    if (is_null($parameterProductValue)) {
                         $param = ParameterValueProduct::create([
                             'product_id' => $items['Prod_ID'],
                             'parameter_id' => $parameter->id,
                             'parameter_value_id' => 0,
                         ]);

                         foreach ($this->langs as $key => $lang) {
                             $param->translations()->create([
                                 'value' => $item,
                                 'lang_id' => $lang->id
                             ]);
                         }
                    }else{
                        $parameterProductValue->update([
                            'parameter_value_id' => 0,
                        ]);
                        $parameterProductValue->translations()->delete();
                        foreach ($this->langs as $key => $lang) {
                            $parameterProductValue->translations()->create([
                                'value' => $item,
                                'lang_id' => $lang->id
                            ]);
                        }
                    }
                }
            }
            }
        }
    }

    public function uploadPrices()
    {
        $data = 'Prices';
        $view = view('admin.google.progressBar', compact('data'));
        echo $view->render();

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(458295368)
                       ->all();

        $sheets = $this->parseSheet($sheets);

        foreach ($sheets as $key => $items) {
            $product = Product::where('id', $items['Prod_ID'])->first();

            if (!is_null($product)) {
                $product->update([
                    // 'discount' => $items['Discount'],
                    'promotion_id' => $items['Promo'],
                ]);
                $this->setProductPrices($product, $items);
            }
        }
    }

    public function setProductPrices($product, $items)
    {
        $autoupload = new AutoUploadController();
        $currencies = Currency::get();
        $mainCurrency = Currency::where('type', 1)->first();
        $mainPrice = ProductPrice::where('currency_id', $mainCurrency->id)->where('product_id', $product->id)->first();

        foreach ($currencies as $key => $currency) {
            $prodPrice = ProductPrice::where('product_id', $product->id)
                                    ->where('currency_id', $currency->id)
                                    ->first();

            if ($currency->abbr == 'EUR') {
                $prodPrice->update([
                        'old_price'     => $items["Retail_old | EUR"],
                        'price'         => $items["Retail_new | EUR"],
                        'b2b_old_price' => $items["B2B_old | EUR"],
                        'b2b_price'     => $items["B2B_new | EUR"],
                ]);
            }elseif($currency->abbr == 'MDL'){
                $prodPrice->update([
                        'old_price'     => $items["Retail_old | MDL"],
                        'price'         => $items["Retail_new | MDL"],
                        'b2b_old_price' => $items["B2B_old | MDL"],
                        'b2b_price'     => $items["B2B_new | MDL"],
                ]);
            }elseif($currency->abbr == 'RON'){
                $prodPrice->update([
                        'old_price'     => $items["Retail_old | RON"],
                        'price'         => $items["Retail_new | RON"],
                        'b2b_old_price' => $items["B2B_old | RON"],
                        'b2b_price'     => $items["B2B_new | RON"],
                ]);
            }

            // $autoupload->generateDillerPrices($product->id);
        }

        // foreach ($currencies as $key => $currency) {
        //     $prodPrice = ProductPrice::where('product_id', $product->id)->where('currency_id', $currency->id)->first();
        //     if ($currency->abbr == 'EUR') {
        //         $prodPrice->update([
        //                 'old_price' => $items["Price Retail (Eur)"],
        //                 'price' => (int)$items["Price Retail (Eur)"] - ((int)$items["Price Retail (Eur)"] * $product->discount / 100),
        //                 'b2b_price' => $items["Price B2B (Eur)"],
        //                 'b2b_old_price' => $items["Price B2B (Eur)"],
        //         ]);
        //     }elseif ($currency->abbr == 'MDL') {
        //         $prodPrice->update([
        //                     'old_price' => $items["Price Retail (MDL)"],
        //                     'price' => (int)$items["Price Retail (MDL)"] - ((int)$items["Price Retail (MDL)"] * $product->discount / 100),
        //                     'b2b_price' => 0,
        //                     'b2b_old_price' => 0,
        //                 ]);
        //     }
        //     $currencies = new CurrenciesController();
        //     $autoupload = new AutoUploadController();
        //
        //     $currencies->countByRateProductsPrice($product, $mainCurrency, $currency);
        //     $autoupload->generateDillerPrices($product->id);
        // }
    }

    public function uploadStocks()
    {
        $data = 'Stocks';
        $view = view('admin.google.progressBar', compact('data'));
        echo $view->render();

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(130534843)
                       ->all();

        $sheets = $this->parseSheet($sheets);
        $warehouses = Warehouse::get();

        foreach ($sheets as $key => $item) {
            if ($item['Product ID'] !== '---') {
                $chekWarehouse = WarehousesStock::where('product_id', $item['Product ID'])
                                ->where('subproduct_id', null)
                                ->where('warehouse_id', 2)
                                ->first();

                Product::where('id', $item['Product ID'])->update([
                    'ean_code' => $item['Ean_Code'],
                ]);
                WarehousesStock::where('product_id', $item['Product ID'])->where('subproduct_id', null)->where('warehouse_id', 1)->update([
                    'stock' => $item['Stock_Frisbo'],
                ]);
                WarehousesStock::where('product_id', $item['Product ID'])->where('subproduct_id', null)->where('warehouse_id', 2)->update([
                    'stock' => 100,
                ]);
            }else{
                SubProduct::where('id', $item['Subroduct ID'])->update([
                    'ean_code' => $item['Ean_Code'],
                ]);
                WarehousesStock::where('subproduct_id', $item['Subroduct ID'])->where('warehouse_id', 1)->update([
                    'stock' => $item['Stock_Frisbo'],
                ]);
                WarehousesStock::where('subproduct_id', $item['Subroduct ID'])->where('warehouse_id', 2)->update([
                    'stock' => 100,
                ]);
            }
        }
    }

    public function uploadCollections()
    {
        $data = 'Collections';
        $view = view('admin.google.progressBar', compact('data'));
        echo $view->render();

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(227503872)
                       ->all();

        $sheets = $this->parseSheetWithLangs($sheets);

        foreach ($sheets as $key => $sheet) {
            $checkCollection = Collection::where('code', $sheet['CollCode'])->first();

            if (!is_null($checkCollection)) {
                $checkCollection->update([
                    'code' => $sheet['CollCode'],
                    'alias' => $sheet['Alias'],
                    'active' => $sheet['Active'] == 'y' ? 1 : 0,
                    'on_home' => $sheet['DisplayHome'] == 'y' ? 1 : 0,
                    'bijoux' => $sheet['ProdsClass'] == 'Bijoux' ? 1 : 0,
                    'homewear' => $sheet['ProdsClass'] == 'Homewear' ? 1 : 0,
                    'position' => $sheet['Order'],
                    'banner_mob' => $sheet['Image-Mob'],
                    'banner' => $sheet['Image-Desktop'],
                ]);

                $checkCollection->translations()->delete();

                foreach ($this->langs as $key => $lang) {
                     $parsedBody = '';
                     Model::$lang = $lang->id;
                     \App::setLocale($lang->lang);

                     $checkCollection->translation()->create([
                        'lang_id'   => $lang->id,
                        'name'      => trans('vars.'.$sheet['CollName']),
                    ]);
                }
            }else{
                $checkCollection = Collection::create([
                    'code' => $sheet['CollCode'],
                    'alias' => $sheet['Alias'],
                    'active' => $sheet['Active'] == 'y' ? 1 : 0,
                    'on_home' => $sheet['DisplayHome'] == 'y' ? 1 : 0,
                    'bijoux' => $sheet['ProdsClass'] == 'Bijoux' ? 1 : 0,
                    'homewear' => $sheet['ProdsClass'] == 'Homewear' ? 1 : 0,
                    'position' => $sheet['Order'],
                    'banner_mob' => $sheet['Image-Mob'],
                    'banner' => $sheet['Image-Desktop'],
                ]);

                foreach ($this->langs as $key => $lang) {
                     $parsedBody = '';
                     Model::$lang = $lang->id;
                     \App::setLocale($lang->lang);

                     $checkCollection->translation()->create([
                        'lang_id'   => $lang->id,
                        'name'      => trans('vars.'.$sheet['CollName']),
                    ]);
                }
            }
        }
    }

    public function uploadSets()
    {
        $data = 'Sets';
        $view = view('admin.google.progressBar', compact('data'));
        echo $view->render();

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(900574299)
                       ->all();

        $sheets = $this->parseSheetWithLangs($sheets);

        foreach ($sheets as $key => $sheet) {
            $findCollection = Collection::where('code', $sheet['CollCode'])->first();
            $checkSet = Set::where('alias', $sheet['Alias'])->first();

            if (!is_null($findCollection)) {
                if (!is_null($checkSet)) {
                    $checkSet->update([
                        'collection_id' => $findCollection->id,
                        'code' => $sheet['SetCode'],
                        'alias' => $sheet['Alias'],
                        'active' => $sheet['Active'] == 'y' ? 1 : 0,
                        'bijoux' => $sheet['ProdsClass'] == 'Bijoux' ? 1 : 0,
                        'homewear' => $sheet['ProdsClass'] == 'Homewear' ? 1 : 0,
                        'position' => $sheet['Order'],
                        'discount' => $sheet['Discount']
                    ]);

                    $checkSet->translations()->delete();

                    foreach ($this->langs as $key => $lang) {
                         Model::$lang = $lang->id;
                         \App::setLocale($lang->lang);

                         $checkSet->translation()->create([
                            'lang_id'   => $lang->id,
                            'name'      => trans('vars.'.$sheet['SetName']),
                        ]);
                    }

                    $checkSet->photos()->delete();

                    for ($i=1; $i < 6; $i++) {
                        if ($sheet['Set-Im-'. $i]) {
                            $checkSet->photos()->create([
                                'type' => 'photo',
                                'src' => $sheet['Set-Im-'. $i],
                           ]);
                        }
                    }
                }else{
                    $newSet = Set::create([
                        'collection_id' => $findCollection->id,
                        'code' => $sheet['SetCode'],
                        'alias' => $sheet['Alias'],
                        'active' => $sheet['Active'] == 'y' ? 1 : 0,
                        'bijoux' => $sheet['ProdsClass'] == 'Bijoux' ? 1 : 0,
                        'homewear' => $sheet['ProdsClass'] == 'Homewear' ? 1 : 0,
                        'position' => $sheet['Order'],
                        'discount' => $sheet['Discount']
                    ]);

                    foreach ($this->langs as $key => $lang) {
                         Model::$lang = $lang->id;
                         \App::setLocale($lang->lang);

                         $newSet->translation()->create([
                            'lang_id'   => $lang->id,
                            'name'      => trans('vars.'.$sheet['SetName']),
                        ]);
                    }
                    for ($i=1; $i < 6; $i++) {
                        if ($sheet['Set-Im-'. $i]) {
                            $newSet->photos()->create([
                                'type' => 'photo',
                                'src' => $sheet['Set-Im-'. $i],
                           ]);
                        }
                    }
                }
            }
        }
    }

    public function addProductsToSets()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 900);

        $data = 'Set Prods';
        $view = view('admin.google.progressBar', compact('data'));
        echo $view->render();

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(1728150457)
                       ->all();

        $sheets = $this->parseSheetWithLangs($sheets);

        foreach ($sheets as $key => $sheet) {
            $findSet = Set::where('code', $sheet['SetCode'])->first();
            $findProduct = Product::where('code', $sheet['SetProdCode'])->first();

            if ($sheet['ProdGift']) {
                $gift = 1;
            }else{
                $gift = 0;
            }

            if (!is_null($findSet)) {
                if (!is_null($findProduct)) {
                    if ($findProduct->subproducts()->count() > 0) {
                        $findRelation = SetProducts::where('set_id', $findSet->id)
                                                    ->where('product_id', $findProduct->id)
                                                    ->where('subproduct_id', 1)
                                                    ->first();
                        if (is_null($findRelation)) {
                            SetProducts::create([
                                'set_id' => $findSet->id,
                                'product_id' => $findProduct->id,
                                'subproduct_id' => 1,
                                'gift' => $gift,
                                'display' => $sheet['DisplayInSet']
                            ]);
                        }else{
                            $findRelation->update([
                                'set_id' => $findSet->id,
                                'product_id' => $findProduct->id,
                                'subproduct_id' => 1,
                                'gift' => $gift,
                                'display' => $sheet['DisplayInSet']
                            ]);
                        }
                        $this->generateSetPrice($findSet);
                    }else{
                        $findRelation = SetProducts::where('set_id', $findSet->id)
                                                    ->where('product_id', $findProduct->id)
                                                    ->first();

                        if (is_null($findRelation)) {
                            SetProducts::create([
                                'set_id' => $findSet->id,
                                'product_id' => $findProduct->id,
                                'gift' => $gift,
                                'display' => $sheet['DisplayInSet']
                            ]);
                        }else{
                            $findRelation->update([
                                'set_id' => $findSet->id,
                                'product_id' => $findProduct->id,
                                'gift' => $gift,
                                'display' => $sheet['DisplayInSet']
                            ]);
                        }
                        $this->generateSetPrice($findSet);
                    }
                }
            }
        }
    }

    public function generateSetPrice($set)
    {
        foreach ($set->products as $key => $product) {
            foreach ($product->prices as $key => $price) {
                if ($product->id == $set->gift_product_id) {
                    $price->update([
                        'set_price' => 1,
                    ]);
                }else{
                    $price->update([
                        'set_price' => $price->old_price - ($price->old_price * $set->discount / 100),
                    ]);
                }
            }
        }
    }

    public function uploadPromotions()
    {
        $data = 'Promotions';
        $view = view('admin.google.progressBar', compact('data'));
        echo $view->render();

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(345801917)
                       ->all();

        $sheets = $this->parseSheetWithLangs($sheets);

        foreach ($sheets as $key => $sheet) {
            $findPromo = Promotion::where('code', $sheet['Promo-ID'])->first();

            if (!is_null($findPromo)) {
                $findPromo->update([
                    'code' => $sheet['Promo-ID'],
                    'alias' => $sheet['Alias'],
                    'img' => $sheet['Image-Mob'],
                    'img_mobile' => $sheet['Image-Desktop'],
                    'discount' => $sheet['Discount'],
                    'position' => $sheet['Order'],
                    'active' => $sheet['Active'] == 'y' ? 1 : 0,
                    'on_home' => $sheet['DisplayHome'] == 'y' ? 1 : 0,
                    'type' => $sheet['PromoType'] == 'products' ? 'prod' : 'set',
                ]);

                $findPromo->translations()->delete();

                foreach ($this->langs as $key => $lang) {
                     Model::$lang = $lang->id;
                     \App::setLocale($lang->lang);

                     $findPromo->translation()->create([
                        'lang_id'   => $lang->id,
                        'name'      => trans('vars.'.$sheet['PromoTitle']),
                        'description' => trans('vars.'.$sheet['PromoSubTitle']),
                        'btn_text'  => trans('vars.'.$sheet['PromoBtn']),
                    ]);
                }
            }else{
                $promo = Promotion::create([
                    'code' => $sheet['Promo-ID'],
                    'alias' => $sheet['Alias'],
                    'img' => $sheet['Image-Mob'],
                    'img_mobile' => $sheet['Image-Desktop'],
                    'discount' => $sheet['Discount'],
                    'position' => $sheet['Order'],
                    'active' => $sheet['Active'] == 'y' ? 1 : 0,
                    'on_home' => $sheet['DisplayHome'] == 'y' ? 1 : 0,
                    'type' => $sheet['PromoType'] == 'products' ? 'prod' : 'set',
                ]);

                foreach ($this->langs as $key => $lang) {
                     Model::$lang = $lang->id;
                     \App::setLocale($lang->lang);

                     $promo->translation()->create([
                        'lang_id'   => $lang->id,
                        'name'      => trans('vars.'.$sheet['PromoTitle']),
                        'description' => trans('vars.'.$sheet['PromoSubTitle']),
                        'btn_text'  => trans('vars.'.$sheet['PromoBtn']),
                    ]);
                }
            }
        }
    }

    public function addProductsToPromos()
    {
        $data = 'Add products to Promotion';
        $view = view('admin.google.progressBar', compact('data'));
        echo $view->render();

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(210554612)
                       ->all();

        $sheets = $this->parseSheet($sheets);

        foreach ($sheets as $key => $sheet) {
            if ($sheet['Promo']) {
                $promoCodes = explode('#', $sheet['Promo']);
                if (count($promoCodes) > 0) {
                    foreach ($promoCodes as $key => $promoCode) {
                        $promotion = Promotion::where('code', $promoCode)->first();
                        if (!is_null($promotion)) {
                            $findRelation = PromotionProduct::where('promotion_id', $promotion->id)->where('product_id', $sheet['Prod_ID'])->first();
                            if (is_null($findRelation)) {
                                PromotionProduct::create([
                                    'promotion_id' => $promotion->id,
                                    'product_id' => $sheet['Prod_ID'],
                                ]);
                            }
                        }
                    }
                }
            }else{
                PromotionProduct::where('product_id', $sheet['Prod_ID'])->delete();
            }
        }
    }

    public function addSetsToPromos()
    {
        $data = 'Add set to Promotion';
        $view = view('admin.google.progressBar', compact('data'));
        echo $view->render();

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(1326065449)
                       ->all();

        $sheets = $this->parseSheet($sheets);

        foreach ($sheets as $key => $sheet) {
            $set = Set::where('code', $sheet['SetCode'])->first();

            if (!is_null($set)) {
                if ($sheet['Promo']) {
                    $promoCodes = explode('#', $sheet['Promo']);
                    if (count($promoCodes) > 0) {
                        foreach ($promoCodes as $key => $promoCode) {
                            $promotion = Promotion::where('code', $promoCode)->first();

                            if (!is_null($promotion)) {
                                $findRelation = PromotionSet::where('promotion_id', $promotion->id)->where('set_id', $set->id)->first();
                                if (is_null($findRelation)) {
                                    PromotionSet::create([
                                        'promotion_id' => $promotion->id,
                                        'set_id' => $set->id,
                                    ]);
                                }
                            }
                        }
                    }
                }else{
                    PromotionSet::where('set_id', $set->id)->delete();
                }
            }
        }
    }

    public function uploadTranslations()
    {
        $data = 'Translations';
        $view = view('admin.google.progressBar', compact('data'));
        echo $view->render();


        $sheets = Sheets::spreadsheet('11LDyQMUp3wMrFKZ8qZdcwbAQuFaOb06rSK4KiDPnYhs')
                       ->sheetById(2105965510)
                       ->all();

        $sheets = $this->parseSheetWithLangs($sheets);

        foreach ($sheets as $key => $sheet) {
            $checkTransGroup = TranslationGroup::where('key', $sheet['group key'])->first();

            if (!is_null($checkTransGroup)) {
                $checkTrans = Translation::where('key', $sheet['trans'])->first();
                if (!is_null($checkTrans)) {
                    foreach ($this->langs as $key => $lang) {
                        TranslationLine::where('translation_id', $checkTrans->id)
                                        ->where('lang_id', $lang->id)
                                        ->update([ 'line' => $sheet[$lang->id], ]);
                    }
                }else{
                    $trans = Translation::create([
                        'group_id' => $checkTransGroup->id,
                        'key' => $sheet['trans'],
                    ]);

                    foreach ($this->langs as $key => $lang) {
                        TranslationLine::create([
                            'translation_id' => $trans->id,
                            'lang_id' => $lang->id,
                            'line' => $sheet[$lang->id],
                        ]);
                    }
                }
            }
        }
    }

    public function uploadImages()
    {
        $data = 'Images';
        $view = view('admin.google.progressBar', compact('data'));
        echo $view->render();
        $handeledImages = [];

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(369147126)
                       ->all();

        $sheets = $this->parseSheet($sheets);

        foreach ($sheets as $key => $sheet) {
            foreach (array_reverse($sheet) as $keySheet => $sheetItem){
                $info = $this->getImageType($keySheet);
                if ($info) {
                    $this->insertImage($info, $sheet, $sheetItem);
                    $handeledImages[] = $sheetItem;
                }
            }
            if ($sheet['Video']){
                $video = $sheet['Video'];
            }else{
                $video = null;
            }
            Product::where('id', $sheet['Prod-ID'])->update(['video' => $video]);
        }

        ProductImage::whereNotIn('href', array_filter($handeledImages))->delete();
    }

    public function insertImage($info, $sheet, $sheetItem)
    {
        if ($sheetItem) {
            // dd($info['type']);
            // Front and Facebook Images
            if ($info['row'] == 'image') {
                if ($info['type'] == 'fb') {
                    $checkFile = file_exists('images/products/fbq/'.$sheetItem);
                }else{
                    $checkFile = file_exists('images/products/og/'.$sheetItem);
                }
                $checkImage = ProductImage::where('href', $sheetItem)->where('product_id', $sheet['Prod-ID'])->first();
                if (is_null($checkImage) && $checkFile) {
                    $image = ProductImage::create([
                                                    'product_id' => $sheet['Prod-ID'],
                                                    'src' => $sheetItem,
                                                    'href' => $sheetItem,
                                                    'type' => $info['type']
                                                ]);

                    $this->setTranslation($image, $sheet['Prod-ID']);
                }elseif (!is_null($checkImage) && $checkFile) {
                    $checkImage->update([
                        'type' => $info['type']
                    ]);
                }
            }

            // Main Image
            if ($info['row'] == 'main') {
                ProductImage::where('product_id', $sheet['Prod-ID'])->where('main', 1)->update([
                    'main' => null,
                ]);
                $findMainImage = ProductImage::where('product_id', $sheet['Prod-ID'])->where('href', $sheetItem)->first();
                if (!is_null($findMainImage)) {
                    $findMainImage->update(['main' => 1, 'type' => null]);
                }else{
                    $checkMainFile = file_exists('images/products/og/'.$sheetItem);
                    if (is_null($findMainImage) &&  $checkMainFile) {
                        $image = ProductImage::create(['product_id' => $sheet['Prod-ID'], 'src' => $sheetItem, 'href' => $sheetItem, 'main' => 1]);
                        $this->setTranslation($image, $sheet['Prod-ID']);
                    }
                }
            }
            // Set Image
            if ($info['row'] == 'set') {
                // ProductImage::where('product_id', $sheet['Prod-ID'])->where('type', 'set')->update([
                //     'type' => null,
                // ]);
                $findSetImage = ProductImage::where('product_id', $sheet['Prod-ID'])
                                            ->where('type', 'set')
                                            ->where('href', $sheetItem)
                                            ->first();

                if (!is_null($findSetImage)) {
                    // $findSetImage->update(['type' => 'set']);
                }else{
                    ProductImage::where('product_id', $sheet['Prod-ID'])->where('type', 'set')->update([
                        'type' => null,
                    ]);
                    $checkFileSet = file_exists('images/products/og/'.$sheetItem);
                    if ($checkFileSet) {
                        $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheetItem, 'href' => $sheetItem, 'type' => 'set']);
                        $this->setTranslation($image, $sheet['Prod-ID']);
                    }
                }
            }
        }
    }

    public function getImageType($sheetOption)
    {
        $data = false;

        if (strpos($sheetOption, 'Site-im') !== false) {
            $data['type'] = null;
            $data['row'] = 'image';
        }

        if (strpos($sheetOption, 'FB-im') !== false) {
            $data['type'] = 'fb';
            $data['row'] = 'image';
        }

        if ($sheetOption == 'Main') {
            $data['type'] = null;
            $data['row'] = 'main';
        }

        if ($sheetOption == 'Set-Image') {
            $data['type'] = null;
            $data['row'] = 'set';
        }

        return $data;
    }

    public function uploadImages_()
    {
        $data = 'Images';
        $view = view('admin.google.progressBar', compact('data'));
        echo $view->render();

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                        // ->sheetList();
                       ->sheetById(2040195145)
                       ->all();
        $sheets = $this->parseSheet($sheets);

        foreach ($sheets as $key => $sheet) {
            $checkImage = ProductImage::where('href', $sheet['Site-im-1'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFile = file_exists('images/products/og/'.$sheet['Site-im-1']);

            if (is_null($checkImage) && $checkFile) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'], 'src' => $sheet['Site-im-1'], 'href' => $sheet['Site-im-1'], 'main' => 1]);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImage = ProductImage::where('href', $sheet['Site-im-2'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFile = file_exists('images/products/og/'.$sheet['Site-im-2']);

            if (is_null($checkImage) && $checkFile) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['Site-im-2'], 'href' => $sheet['Site-im-2'],'main' => 0]);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImage = ProductImage::where('href', $sheet['Site-im-3'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFile = file_exists('images/products/og/'.$sheet['Site-im-3']);

            if (is_null($checkImage) && $checkFile) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['Site-im-3'], 'href' => $sheet['Site-im-3'],'main' => 0]);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImage = ProductImage::where('href', $sheet['Site-im-4'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFile = file_exists('images/products/og/'.$sheet['Site-im-4']);

            if (is_null($checkImage) && $checkFile) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['Site-im-4'], 'href' => $sheet['Site-im-4'],'main' => 0]);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImage = ProductImage::where('href', $sheet['Site-im-5'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFile = file_exists('images/products/og/'.$sheet['Site-im-5']);

            if (is_null($checkImage) && $checkFile) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['Site-im-5'], 'href' => $sheet['Site-im-5'],'main' => 0]);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-1'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-1']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-1'], 'href' => $sheet['FB-im-1'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-2'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-2']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-2'], 'href' => $sheet['FB-im-2'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-3'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-3']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-3'], 'href' => $sheet['FB-im-3'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-4'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-4']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-4'], 'href' => $sheet['FB-im-4'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-5'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-5']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-5'], 'href' => $sheet['FB-im-5'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-6'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-6']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-6'], 'href' => $sheet['FB-im-6'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-7'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-7']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-7'], 'href' => $sheet['FB-im-7'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-8'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-8']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-8'], 'href' => $sheet['FB-im-8'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-9'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-9']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-9'], 'href' => $sheet['FB-im-9'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-10'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-10']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-10'], 'href' => $sheet['FB-im-10'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            // Main Image
            // if (array_key_exists('Main', $sheet)) {
                ProductImage::where('product_id', $sheet['Prod-ID'])->where('main', 1)->update([
                    'main' => null,
                ]);
                $findMainImage = ProductImage::where('product_id', $sheet['Prod-ID'])->where('href', $sheet['Main'])->first();
                if (!is_null($findMainImage)) {
                    $findMainImage->update(['main' => 1]);
                }else{
                    $checkFileSet = file_exists('images/products/og/'.$sheet['Main']);
                    if (is_null($findMainImage) &&  $checkFileSet) {
                        $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['Main'], 'href' => $sheet['Main'], 'main' => 1]);
                        $this->setTranslation($image, $sheet['Prod-ID']);
                    }
                }
            // }

            // Set Image
            // if (array_key_exists('Set-Image', $sheet)) {
                ProductImage::where('product_id', $sheet['Prod-ID'])->where('type', 'set')->update([
                    'type' => 0,
                ]);

                $findSetImage = ProductImage::where('product_id', $sheet['Prod-ID'])
                                        ->where('href', $sheet['Set-Image'])
                                        ->first();

                if (!is_null($findSetImage)) {
                    $findSetImage->update(['type' => 'set']);
                }else{
                    $checkFileSet = file_exists('images/products/og/'.$sheet['Set-Image']);

                    if ($checkFileSet) {
                        $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['Set-Image'], 'href' => $sheet['Set-Image'], 'type' => 'set']);
                        $this->setTranslation($image, $sheet['Prod-ID']);
                    }
                }

            Product::where('id', $sheet['Prod-ID'])->update(['video' => $sheet['Video']]);
        }
    }

    public function setTranslation($image, $productId)
    {
        foreach ($this->langs as $lang){
            ProductImageTranslation::create( [
                'product_image_id' => $image->id,
                'lang_id' =>  $lang->id,
            ]);
        }
    }

    public function getParametersId()
    {
        $products = Product::get();
        $parameters = Parameter::orderBy('type', 'asc')->get();
        $promotions = Promotion::get();

        return view('admin.google.parametersIdList', compact('products', 'parameters', 'promotions'));
    }

    public function getSubproductsId()
    {
        $products = Product::get();

        return view('admin.google.subproductsIdList', compact('products'));
    }

    public function parseSheet($sheets)
    {
        $keys = $sheets[0];
        $arr = [];

        foreach ($sheets as $key => $sheet) {
            if ($key !== 0) {
                for ($i=0; $i < count($keys); $i++) {
                    if (array_key_exists($i, $sheet)) {
                        $arr[$key][$keys[$i]] = $sheet[$i];
                    }else{
                        $arr[$key][$keys[$i]] = 0;
                    }
                }
            }
        }
        return $arr;
    }

    public function parseSheetWithLangs($sheets)
    {
        $keys = $sheets[0];
        $arr = [];

        foreach ($sheets as $key => $sheet) {
            if ($key !== 0) {
                for ($i=0; $i < count($keys); $i++) {
                    $langKey = $this->getLangId($keys[$i]);
                    if ($langKey > 0) {
                        $keys[$i] = $langKey;
                    }
                    if (array_key_exists($i, $sheet)) {
                        $arr[$key][$keys[$i]] = $sheet[$i];
                    }else{
                        $arr[$key][$keys[$i]] = 0;
                    }
                }
            }
        }
        return $arr;
    }

    public function getLangId($langAbbr)
    {
        $ret = 0;
        foreach ($this->langs as $key => $lang) {
            if ($langAbbr == $lang->lang) {
                $ret = $lang->id;
            }
        }

        return $ret;
    }


    public function getProducts()
    {
        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(config('sheets.post_sheet_id'))
                       ->all();

        return view('admin.google.progressBar');
    }
}

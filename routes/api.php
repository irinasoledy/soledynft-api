<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/api/offers', 'API\OfferController@getOffers');
Route::post('/en/api/offer', 'API\OfferController@createOffer');


Route::patch('/api/cart', 'API\CheckoutController@changeQtyCart')->middleware('cors');
Route::delete('/api/cart', 'API\CheckoutController@deleteCart')->middleware('cors');
Route::delete('/api/carts', 'API\CheckoutController@deleteAllCarts')->middleware('cors');
Route::post('/api/cart', 'API\CheckoutController@setCart');


Route::post('/en/api/cart', 'API\CheckoutController@setCart');
Route::patch('/en/api/cart', 'API\CheckoutController@changeQtyCart');
Route::delete('/en/api/cart', 'API\CheckoutController@deleteCart');
Route::delete('/en/api/carts', 'API\CheckoutController@deleteAllCarts');


Route::group(['prefix' => 'api'], function () {
    Route::get('settings', 'API\SettingsController@getSettings');
    Route::get('translations', 'API\SettingsController@getTranslations');
    Route::get('banners', 'API\SettingsController@getBanners');
    Route::get('static-pages', 'API\SettingsController@getStaticPages');

    Route::get('categories', 'API\ProductsController@getCategories');
    Route::get('category', 'API\ProductsController@getCategory');
    Route::get('product', 'API\ProductsController@getProduct');

    Route::get('products/new', 'API\ProductsController@getNewProducts');
    Route::get('products/outlet', 'API\ProductsController@getOutletProducts');
    Route::get('products/all', 'API\ProductsController@getAllProducts');

    Route::get('products/sort', 'API\ProductsController@getSortedProducts');
    Route::get('products/filter', 'API\ProductsController@getFiltredProducts');
    Route::get('products/default-filter', 'API\ProductsController@getDefaultFilter');


    Route::get('collections', 'API\ProductsController@getCollections');
    Route::get('collection', 'API\ProductsController@getCollection');

    Route::get('promotions', 'API\ProductsController@getPromotions');

    Route::get('set/cart', 'API\CheckoutController@setCart'); // Remake to post

    Route::get('cart', 'API\CheckoutController@getCart');

    Route::get('designers', 'API\ProductsController@getDesigners');
    Route::get('designer', 'API\ProductsController@getDesigner');
});


Route::group(['prefix' => 'api/v2', 'middleware' => 'cors'], function () {
    Route::get('categories', 'Api\ProductsController@getCategories');

    Route::get('data', 'Api\ServiceController@initData');

    Route::get('translations', 'API\TranslationsController@all');

    Route::get('promotions', 'Api\PromotionController@get');

    Route::get('leads', 'Api\ServiceController@addLeads');  // to remake POST

});

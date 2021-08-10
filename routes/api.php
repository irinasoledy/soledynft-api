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

Route::post('/api/cart', function() {
    dd('super');
})->middleware('cors');

Route::post('/en/api/cart', function() {
    dd('super');
})->middleware('cors');

Route::group(['prefix' => 'api'], function()
{
    Route::get('settings', 'API\SettingsController@getSettings');

    Route::get('categories', 'API\ProductsController@getCategories');
    Route::get('category', 'API\ProductsController@getCategory');
    Route::get('product', 'API\ProductsController@getProduct');

    Route::get('products/new', 'API\ProductsController@getNewProducts');
    Route::get('products/outlet', 'API\ProductsController@getOutletProducts');

    Route::get('products/sort', 'API\ProductsController@getSortedProducts');
    Route::get('products/filter', 'API\ProductsController@getFiltredProducts');
    Route::get('products/default-filter', 'API\ProductsController@getDefaultFilter');


    Route::get('collections', 'API\ProductsController@getCollections');
    Route::get('collection', 'API\ProductsController@getCollection');

    Route::get('promotions', 'API\ProductsController@getPromotions');

    Route::get('set/cart', 'API\CheckoutController@setCart'); // Remake to post

    Route::get('cart', 'API\CheckoutController@getCart');
    // Route::post('cart', 'API\CheckoutController@setCart');

});


Route::group(['prefix' => 'api/v2', 'middleware' => 'cors'], function()
{
    Route::get('categories', 'Api\ProductsController@getCategories');

    Route::get('data', 'Api\ServiceController@initData');

    Route::get('translations', 'Api\TranslationsController@all');

    Route::get('promotions', 'Api\PromotionController@get');

    Route::get('leads', 'Api\ServiceController@addLeads');  // to remake POST

});

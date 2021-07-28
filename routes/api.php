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

Route::group(['prefix' => 'api'], function()
{
    Route::get('categories', 'API\ProductsController@getCategories');
    Route::get('collections', 'API\ProductsController@getCollections');
    Route::get('promotions', 'API\ProductsController@getPromotions');
});

Route::group(['prefix' => 'api/v2', 'middleware' => 'cors'], function()
{
    Route::get('categories', 'Api\ProductsController@getCategories');


    Route::get('data', 'Api\ServiceController@initData');

    Route::get('translations', 'Api\TranslationsController@all');

    Route::get('promotions', 'Api\PromotionController@get');

    Route::get('leads', 'Api\ServiceController@addLeads');  // to remake POST

});

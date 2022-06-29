<?php
$prefix = session('applocale');
$types = ['homewear', 'bijoux'];

Route::get('/', function () {
    return redirect('/auth/login');
});

Route::get('/generate_pdf', 'PaymentController@generatePdf');
Route::get('/sitemap.xml', 'SitemapController@index');

Route::get('/status/{orderId}', 'Payments\Methods\Paypal@getPaymentStatus')->name('status');
Route::get('/cancel-status/{orderId}', 'Payments\Methods\Paypal@getPaymentCancelStatus')->name('cancel-status');
Route::any('/paypal/callback', 'Payments\Methods\Paypal@callBack');
Route::any('/paydo/callback', 'Payments\Methods\Paydo@callBack');

Route::any('/payment/callback', 'Payments\Paynet@callBackLink');
Route::any('/paynet/callback', 'PaymentController@paynetCallback');


// Front routes
Route::group(['prefix' => $prefix], function () use ($types) {
    Route::get('/oops', 'PagesController@getOopsPage')->name('oops');
    Route::get('/cart', 'CartController@index')->name('cart');
    Route::get('/wish', 'WishListController@index');

    // order
    Route::get('/order', 'CheckoutController@renderCheckoutShipping')->name('order');
    Route::get('/order/payment/{orderId}', 'CheckoutController@renderCheckoutPayment')->name('order-payment');
    Route::get('/thanks', 'CheckoutController@renderThankyouPage')->name('thanks');

    Route::get('/paydo/payment/success/{orderId}/{payment}', 'Payments\Methods\Paydo@getSuccessStatus')->name('paydo-success');
    Route::get('/paydo/payment/fail/{orderId}/{payment}', 'Payments\Methods\Paydo@getFailStatus')->name('paydo-fail');

    Route::get('/login/{provider}', 'AuthController@redirectToProvider');
    Route::get('/login/{provider}/callback', 'AuthController@handleProviderCallback');

    //guest user settings
    Route::post('set-user-settings', 'Controller@setUserSettings');

    Route::get('/', 'PagesController@index')->name('home');
    Route::get('/home', 'PagesController@index')->name('home');
    Route::get('/new', 'ProductsController@renderNewIn')->name('dynamic');
    Route::get('/sale', 'ProductsController@renderOutlet')->name('dynamic');
    Route::get('/promos', 'ProductsController@renderPromos')->name('dynamic');
    Route::get('/promos/prod/{id}', 'ProductsController@renderProductPromo')->name('dynamic');
    Route::get('/promos/set/{id}', 'ProductsController@renderSetPromo')->name('dynamic');
    // Static Pages
    Route::get('/{pages}', 'PagesController@getPages')->name('pages');

    foreach ($types as $key => $type) {
        Route::group(['prefix' => $type], function () {
            Route::post('/contact-feed-back', 'FeedBackController@contactFeedBack');
            Route::post('/save-country-user', 'Controller@saveCountryUser');

            Route::get('/catalog/all', 'ProductsController@categoryRenderAll')->name('dynamic');
            Route::get('/catalog/{category}', 'ProductsController@categoryRender')->name('dynamic');
            Route::get('/catalog/all', 'ProductsController@categoryRenderAll')->name('dynamic');
            Route::get('/catalog/{category}/{product}', 'ProductsController@productRender')->name('dynamic');
            // Route::get('/promotions', 'ProductsController@renderPromotions')->name('dynamic');

            Route::get('/collection/{collection}', 'ProductsController@collectionRender')->name('dynamic');

            Route::get('/logout', 'AuthController@logout');
            Route::get('/login', 'AuthController@renderLogin');
        });
    }

    // Localization
    Cache::forget('lang.js');
    Route::get('/js/lang.js', 'LanguagesController@changeLangScript')->name('assets.lang');
});

// Personal Account routes
Route::group(['prefix' => $prefix, 'middleware' => 'auth_front'], function () {
    Route::get('/account/personal-data', 'AccountController@index')->name('account');
    Route::get('/account/promocodes', 'AccountController@getPromocodes')->name('account-promocodes');
    Route::get('/account/cart', 'AccountController@getCart')->name('account-cart');
    Route::get('/account/wishlist', 'AccountController@getWishlist')->name('account-wishlist');
    Route::post('/account/savePersonalData', 'AccountController@savePersonalData')->name('account.savePersonalData');
    Route::post('/account/changePass', 'AccountController@savePass')->name('account.savePass');
    Route::post('/account/addAddress', 'AccountController@addAddress')->name('account.addAddress');
    Route::post('/account/saveAddress/{id}', 'AccountController@saveAddress')->name('account.saveAddress');
    Route::get('/account/remove-account', 'AccountController@removeAccount')->name('account.removeAccount');

    Route::get('/account/history', 'AccountController@history')->name('account.history');
    Route::post('/account/historyCart/{id}', 'AccountController@historyCart')->name('account.historyCart');
    Route::get('/account/history/order/{order}', 'AccountController@historyOrder')->name('account.historyOrder');
    Route::post('/account/historyCartProduct/{id}', 'AccountController@historyCartProduct')->name('account.historyCartProduct');

    Route::get('/account/returns', 'AccountController@returns')->name('account.returns');
    Route::get('/account/returns/create', 'AccountController@createReturn');
    Route::get('/account/returns/create/{id}', 'AccountController@createReturnFromOrder');
    Route::post('/account/returns/store', 'AccountController@storeReturn');
    Route::get('/account/returns/{id}', 'AccountController@showReturn');

    Route::get('/account/return', 'AccountController@return')->name('account.return');
    Route::get('/account/return/order/{order}', 'AccountController@returnOrder')->name('account.returnOrder');
    Route::post('/account/return/addProductsToReturn/{order}', 'AccountController@addProductsToReturn')->name('account.addProductsToReturn');
    Route::post('/account/return/saveReturn/{return}', 'AccountController@saveReturn')->name('account.saveReturn');
});

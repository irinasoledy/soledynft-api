<?php

$prefix = session('applocale');
$types = ['homewear', 'bijoux'];

Route::group(['prefix' => $prefix], function () use ($types) {
    foreach ($types as $key => $type) {
        Route::group(['prefix' => $type], function () {
            // Home Routes
            Route::post('/get-category-home', 'ProductsController@getCategoriesOnHome');
            Route::post('/accept-cookie-policy', 'Controller@acceptCookiePolicy');

            // Auth
            Route::post('/auth-get-phone-codes-list', 'AuthController@getPhoneCodesList');
            Route::post('/settings/save-settings', 'Controller@saveSettings');
            Route::post('/settings/get-currency', 'Controller@getCurrency');
            Route::post('/settings/get-country', 'Controller@getCountry');

            // Categories
            Route::post('/get-sets', 'ProductsController@getSets');

            // Outlet
            Route::post('/get-sale-products', 'ProductsController@getSaleProducts');

            // New Products
            Route::post('/get-new-products', 'ProductsController@getNewProducts');

            // Recently Products
            Route::post('/get-recently-products', 'ProductsController@getRecentlyProducts');

            // Filter
            Route::post('/filter', 'ProductsController@filter');
            Route::post('/setDefaultFilter', 'ProductsController@setDefaultFilter');

            // Subproducts
            Route::post('/get-subproduct', 'ProductsController@getSubproductVue');

            // Carts Routes
            Route::post('/get-cart-items', 'CartController@getCartItems');
            Route::post('/add-product-to-cart', 'CartController@addProductToCart');
            Route::post('/add-set-to-cart', 'CartController@addSetToCart');
            Route::post('/add-mix-set-to-cart', 'CartController@addMixSetToCart');
            Route::post('/change-country', 'CartController@changeCountry');
            Route::post('/add-set-to-wish', 'WishListController@addSetToWish');

            Route::post('/remove-all-cart', 'CartController@removeAllCart');
            Route::post('/remove-cart-item', 'CartController@deleteProductFromCart');
            Route::post('/remove-set-discount', 'CartController@diableSetDiscount');
            Route::post('/remove-cart-set', 'CartController@deleteSetFromCart');
            Route::post('/change-product-qty', 'CartController@changeProductQty');
            Route::post('/move-product-to-wish', 'CartController@moveProductToWish');
            Route::post('/get-countries', 'CartController@getCountries');
            Route::post('/change-currency', 'CartController@changeCurrency');
            Route::post('/set-country-delivery', 'CartController@setCountryDelivery');

            Route::post('/exchange-shipping-price', 'CartController@exchangeShippingPrice');

            Route::post('/check-promocode', 'CartController@checkPromocode');
            Route::post('/apply-promocode', 'CartController@applyPromocode');

            // Rent
            Route::post('/rent-product', 'FeedBackController@rentProduct');

            // Favorites
            Route::post('/get-wish-items', 'WishListController@getWishItems');
            Route::post('/add-to-favorites', 'WishListController@addToFavorites');

            Route::post('/auth-login', 'AuthController@login');
            Route::post('/registration', 'AuthController@register');
            Route::post('/auth-guest-login', 'AuthController@loginAsGuest');
            Route::post('/checkAuth', 'AuthController@checkAuth');

            Route::post('/reset-password-send-email', 'AuthController@sendEmailCode');
            Route::post('/reset-password-send-code', 'AuthController@confirmEmailCode');
            Route::post('/reset-password-send-password', 'AuthController@changePassword');

            // Wish
            Route::post('/moveProductToCart', 'WishListController@moveProductToCart');
            Route::post('/removeProductWish', 'WishListController@removeProductWish');
            Route::post('/removeSetWish', 'WishListController@removeSetWish');

            // Checkout Actions
            Route::post('/order-shipping', 'CheckoutController@storeShippingDetails');
            Route::post('/order-get-user', 'CheckoutController@getUser');
            Route::post('/order-change-country', 'CheckoutController@changeCountry');
            Route::post('/order-validate-stocks', 'CheckoutController@validateStocks');

            Route::post('/order-get-payments', 'PaymentController@getPaymentMethods');
            Route::get('/order/payment/methods/{methodId}/{amount}/{orderId}/{payment}', 'CheckoutController@handlePreorder');

            // Order
            Route::get('/cart/getUserdata', 'CartController@getUserdata');
            Route::get('/cart/getAddressdata', 'CartController@getAddressdata');
        });
    }
});

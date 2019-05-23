<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@home')->name('home');

Route::get('/products/list', 'HomeController@products_list')->name('products_list');

Route::get('/shopping-cart', 'HomeController@cart')->name('cart');

Route::get('/shopping-cart/add', 'HomeController@add_cart')->name('add_cart');

Route::get('/shopping-cart/remove', 'HomeController@remove_cart')->name('remove_cart');

Route::get('/shopping-cart/use-promotion', 'HomeController@use_promotion')->name('use_promotion');

Route::get('/checkout', 'HomeController@checkout')->name('checkout');

Route::get('/checkout/success', 'HomeController@success')->name('success')->middleware('auth');

Route::get('/products/product-detail/{code}', 'HomeController@detail')->name('detail');


Auth::routes();

Route::name('admin.')->group(function () {
    Route::group(['middleware' => ['auth','admin']], function () {
        Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
        
        Route::get('/users', 'HomeController@users')->name('user');
        Route::get('/users/profiles/{user_slug}', 'HomeController@profiles')->name('user_profile');
        Route::get('/users/profiles/{user_slug}/edit', 'HomeController@profiles_edit')->name('user_profile_edit');
        Route::get('/users/add', 'HomeController@add')->name('user_add');

        Route::get('/products', 'HomeController@products')->name('product');
        Route::get('/products/add', 'HomeController@add_product')->name('add_product');
        Route::get('/products/edit/{product_slug}', 'HomeController@edit_product')->name('edit_product');

        Route::get('/promotions', 'HomeController@promotions')->name('promotion');

        Route::get('/orders', 'HomeController@orders')->name('order');
    });
});
Route::prefix('api')->group(function () {

    Route::group(['middleware' => ['auth','admin']], function () {
        Route::post('products', 'ProductController@add');
        Route::put('products/{id}', 'ProductController@update')->name('api_product_update');
        Route::delete('products/{id}', 'ProductController@destroy');
    });
    Route::get('products', 'ProductController@getMany');
    Route::get('products/{id}', 'ProductController@get');
    
    Route::group(['middleware' => ['auth','admin']], function () {
        Route::post('promotions', 'PromotionController@add');
        Route::put('promotions/{id}', 'PromotionController@update')->name('api_promotion_update');
        Route::delete('promotions/{id}', 'PromotionController@destroy');
    });
    Route::get('promotions', 'PromotionController@getMany');
    Route::get('promotions/{id}', 'PromotionController@get');

    Route::group(['middleware' => ['auth','admin']], function () {
        Route::get('orders', 'OrderController@getMany');
        Route::put('orders/{id}', 'OrderController@update')->name('api_order_update');
    });
    Route::post('orders', 'OrderController@add');
    Route::get('orders/{id}', 'OrderController@get');

    Route::get('users', 'UserController@getMany');
    Route::get('users/{id}', 'UserController@get');
    Route::group(['middleware' => ['auth','admin']], function () {
        Route::post('users', 'UserController@add');
        Route::put('users/{id}', 'UserController@update')->name('api_user_update');
        Route::delete('users/{id}', 'UserController@destroy');
    });
});

// Test
Route::get('/server-test', 'UserController@test');
<?php

use App\Helpers\Router;

Router::get('/', 'ProductController@index');
Router::post('/product/create', 'ProductController@create');

Router::get('/', 'ProductController@index');
Router::post('/product/store', 'ProductController@store');

Router::get('/product/edit', 'ProductController@edit');
Router::post('/product/update', 'ProductController@update');


Router::post('/cart/add', 'CartController@add');
Router::get('/cart', 'CartController@view');

Router::get('/checkout', 'CartController@checkout');
Router::post('/checkout/save', 'CartController@saveOrder');
Router::get('/cep', 'CartController@cepLookup');

Router::post('/cart/coupon', 'CartController@applyCoupon');

Router::post('/webhook', 'OrderController@webhook');

Router::get('/api/variations', 'ProductController@variations');

Router::get('/cart/clear', 'CartController@clear');

Router::post('/product/delete', 'ProductController@delete');

Router::post('/cart/add-ajax', 'CartController@addAjax');
Router::get('/cart/items', 'CartController@itemsJson');

Router::post('/cart/remove', 'CartController@remove');
Router::post('/cart/coupon/remove', 'CartController@removeCoupon');

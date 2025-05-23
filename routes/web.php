<?php

use App\Helpers\Router;
use App\Controllers\CartController;

Router::get('/', 'ProductController@index');
Router::post('/product/create', 'ProductController@create');

Router::get('/', 'ProductController@index');
Router::post('/product/store', 'ProductController@store');

Router::get('/product/edit', 'ProductController@edit');
Router::post('/product/update', 'ProductController@update');


Router::post('/cart/add', 'CartController@add');
Router::get('/cart', 'CartController@view');

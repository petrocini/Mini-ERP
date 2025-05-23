<?php

use App\Helpers\Router;

Router::get('/', 'ProductController@index');
Router::post('/product/create', 'ProductController@create');

Router::get('/', 'ProductController@index');
Router::post('/product/store', 'ProductController@store');
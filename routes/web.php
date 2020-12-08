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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::match(array('GET', 'POST'), 'logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home', 'HomeController@home')->name('home');

Route::get('/subscribers/create', 'SubscribersController@create');
Route::post('/subscribers', 'SubscribersController@store');
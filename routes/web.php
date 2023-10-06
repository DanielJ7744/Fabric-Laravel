<?php

use Illuminate\Support\Facades\Route;

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

Route::get('sso/{service}', 'Auth\SocialiteController@redirectToProvider')->name('sso.provider');
Route::get('sso/{service}/callback', 'Auth\SocialiteController@handleProviderCallback')->name('sso.callback');

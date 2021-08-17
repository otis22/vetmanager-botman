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

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');
Route::get('/stats', 'BotManController@stats');
Route::post('/event/{domain}', 'NotificationsController@handleNotifications');
Route::get('/price/{md5}/{clinicId}/', 'PriceController@groups');
Route::get('/price/{md5}/{clinicId}/{groupId}', 'PriceController@priceList');
Route::get('/shield/{md5}/all', 'ServiceController@visits');
Route::get('/shield/{md5}/today', 'ServiceController@todayCount');
Route::get('/shield/{md5}/week', 'ServiceController@weekCount');

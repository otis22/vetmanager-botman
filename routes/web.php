<?php

use Illuminate\Support\Facades\Route;
use App\Conversations\VisitConversation;

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

//Route::get('/service', function (){
//    return view('service');
//})->name('service');

//Route::match(['get', 'post'], '/service', 'VisitController');



Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');
Route::get('/stats', 'BotManController@stats');
Route::post('/event/{domain}', 'NotificationsController@handleNotifications');
Route::get('/price/{md5}/{clinicId}/', 'PriceController@groups');
Route::get('/price/{md5}/{clinicId}/{groupId}', 'PriceController@priceList');

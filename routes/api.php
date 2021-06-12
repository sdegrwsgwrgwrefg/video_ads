<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/login', function (Request $request) {
//     echo '33';
// });

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'App\Http\Controllers\Auth\ApiLoginController@userLogin');
    Route::post('signup', 'App\Http\Controllers\Auth\ApiLoginController@userRegister');
  
    Route::group([
     'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'App\Http\Controllers\Auth\ApiLoginController@logout');
        Route::get('user', 'App\Http\Controllers\Auth\ApiLoginController@user');
    });
});
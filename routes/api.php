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

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
 
Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', 'UserController@logout');
 
    Route::get('user', 'UserController@getAuthUser');
 
    Route::get('loans', 'LoanController@index');
    Route::get('loans/{id}', 'LoanController@show');
    Route::post('loans', 'LoanController@store');
    Route::put('loans/{id}', 'LoanController@update');
    Route::delete('loans/{id}', 'LoanController@destroy');
});

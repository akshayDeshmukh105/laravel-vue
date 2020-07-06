<?php

use Illuminate\Http\Request;

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

Route::group([], function () {
    Route::post('login', 'UserController@login');
});

//Route::middleware(['auth:api', 'auth.status'])->group(function () {
Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'UserController@getUsers');
        Route::post('/create', 'UserController@createUser');
        Route::post('/update/{id}', 'UserController@updateUser');
        Route::delete('/{id}', 'UserController@deleteUser');
    });
});

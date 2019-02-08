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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::get('/products/{variant}', 'ProductController@index');

Route::get('/product/{id}', 'ProductController@show');

Route::post('/product/add', 'ProductController@store');

Route::put('/product/edit', 'ProductController@store');

Route::delete('/product/{id}/{variant}', 'ProductController@destroy');

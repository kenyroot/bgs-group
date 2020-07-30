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

Route::get('members','ApiMemberController@get')->middleware(\App\Http\Middleware\TokenAuthenticate::class);
Route::post('add-member','ApiMemberController@add')->middleware(\App\Http\Middleware\TokenAuthenticate::class);
Route::post('update-member','ApiMemberController@update')->middleware(\App\Http\Middleware\TokenAuthenticate::class);
Route::delete('member','ApiMemberController@delete')->middleware(\App\Http\Middleware\TokenAuthenticate::class);

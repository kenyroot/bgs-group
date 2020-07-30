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
Route::post('member','ApiMemberController@add')->middleware(\App\Http\Middleware\TokenAuthenticate::class);
Route::put ('member/{id}','ApiMemberController@update')->middleware(\App\Http\Middleware\TokenAuthenticate::class);
Route::delete('member/{id}','ApiMemberController@delete')->middleware(\App\Http\Middleware\TokenAuthenticate::class);

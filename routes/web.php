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
Route::group(['middleware' => ['user.guest']], function () {
    Route::get('/login', 'Auth\Login@index')->name('login');
    Route::post('/login', 'Auth\Login@verify')->name('loginVerify');
    Route::get('/register', 'Auth\Register@index')->name('register');
    Route::post('/register', 'Auth\Register@create')->name('registerVerify');
});

Route::group(['middleware' => ['auth.custom']], function () {
    Route::get('/logout', 'Auth\Logout@index')->name('logout');
    Route::get('/system', 'System@index')->name('system');

    // ALL VALIDATED ROUTES GOES HERE
    Route::group(['middleware' => ['user.valid']], function () {

    });
});
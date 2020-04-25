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
        Route::get('/system/managerole', 'ManageRole@index')->name('managerole')->middleware(['access.role:super']);
        Route::get('/system/managerole/permission/{id}', 'ManageRole@permission')->name('managerole_permission')->middleware(['access.role:super']);
        Route::get('/system/managerole/permission/add/{id}', 'ManageRole@permissionadd')->name('managerole_permissionadd')->middleware(['access.role:super']);
        Route::post('/system/managerole/permission/add/{id}', 'ManageRole@permissionaddpost')->middleware(['access.role:super']);
        Route::get('/system/managerole/permission/edit/{id}', 'ManageRole@permissionedit')->name('managerole_permissionedit')->middleware(['access.role:super']);
        Route::post('/system/managerole/permission/edit/{id}', 'ManageRole@permissioneditpost')->middleware(['access.role:super']);
        Route::get('/system/managerole/permission/delete/{id}', 'ManageRole@permissiondelete')->name('managerole_permissiondelete')->middleware(['access.role:super,admin,busmanager']);
        Route::get('/system/permission/user/{id}', 'ManageRole@permissionuser')->name('managerole_permissionuser')->middleware(['access.role:super,admin']);
        Route::post('/system/permission/user/{id}', 'ManageRole@permissionusermodify')->middleware(['access.role:super,admin,busmanager']);

        Route::get('/system/validation', 'Validation@index')->name('validation');
        Route::get('/system/validation/validate/{id}', 'Validation@validated')->name('validation_validate');
        Route::get('/system/validation/remove/{id}', 'Validation@delete')->name('validation_delete');
    });
});
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

        // Site Admins
        Route::get('/system/admin', 'Admin@index')->name('admin');
        Route::get('/system/admin/add', 'Admin@add')->name('adminadd');
        Route::post('/system/admin/add', 'Admin@addpost');
        Route::get('/system/admin/edit/{id}', 'Admin@edit')->name('adminedit');
        Route::post('/system/admin/edit/{id}', 'Admin@editpost');
        Route::get('/system/admin/delete/{id}', 'Admin@delete')->name('admindelete');

        // Bus Manager Verification
        Route::get('/system/validation', 'Validation@index')->name('validation');
        Route::get('/system/validation/validate/{id}', 'Validation@validated')->name('validation_validate');
        Route::get('/system/validation/remove/{id}', 'Validation@delete')->name('validation_delete');

        // Support Staff
        Route::get('/system/supportstaff', 'SupportStaff@index')->name('supportstaff');
        Route::get('/system/supportstaff/add', 'SupportStaff@add')->name('supportstaffadd');
        Route::post('/system/supportstaff/add', 'SupportStaff@addpost');
        Route::get('/system/supportstaff/edit/{id}', 'SupportStaff@edit')->name('supportstaffedit');
        Route::post('/system/supportstaff/edit/{id}', 'SupportStaff@editpost');
        Route::get('/system/supportstaff/delete/{id}', 'SupportStaff@delete')->name('supportstaffdelete');
        Route::post('/system/supportstaff/ajax/search', 'SupportStaff@ajaxsearch')->name('ajax_search_supportstaff');

        //Bus Manager
        Route::get('/system/busmanager', 'BusManager@index')->name('busmanager')->middleware(['access.feature:viewbusmanager']);
        Route::get('/system/busmanager/edit/{id}', 'BusManager@edit')->name('busmanageredit')->middleware(['access.feature:editbusmanager']);
        Route::post('/system/busmanager/edit/{id}', 'BusManager@editpost')->middleware(['access.feature:editbusmanager']);
        Route::get('/system/busmanager/delete/{id}', 'BusManager@delete')->name('busmanagerdelete')->middleware(['access.feature:removebusmanager']);
        Route::post('/system/busmanager/ajax/search', 'BusManager@ajaxsearch')->name('ajax_search_busmanager');

        //Bus Counter
        Route::get('/system/buscounter', 'BusCounter@index')->name('buscounter')->middleware(['access.feature:viewbuscounter']);
        Route::get('/system/buscounter/add', 'BusCounter@add')->name('buscounteradd');
        Route::post('/system/buscounter/add', 'BusCounter@addpost');
        Route::get('/system/buscounter/edit/{id}', 'BusCounter@edit')->name('buscounteredit')->middleware(['access.feature:editbuscounter']);
        Route::post('/system/buscounter/edit/{id}', 'BusCounter@editpost')->middleware(['access.feature:editbuscounter']);
        Route::get('/system/buscounter/delete/{id}', 'BusCounter@delete')->name('buscounterdelete')->middleware(['access.feature:removebuscounter']);
        Route::post('/system/buscounter/ajax/search', 'BusCounter@ajaxsearch')->name('ajax_search_buscounter');

        //Counter Staff
        Route::get('/system/counterstaff', 'CounterStaff@index')->name('counterstaff')->middleware(['access.feature:viewcounterstaff']);
        Route::get('/system/counterstaff/add', 'CounterStaff@add')->name('counterstaffadd');
        Route::post('/system/counterstaff/add', 'CounterStaff@addpost');
        Route::get('/system/counterstaff/edit/{id}', 'CounterStaff@edit')->name('counterstaffedit');
        Route::post('/system/counterstaff/edit/{id}', 'CounterStaff@editpost');
        Route::get('/system/counterstaff/delete/{id}', 'CounterStaff@delete')->name('counterstaffdelete');
        Route::post('/system/counterstaff/ajax/search', 'CounterStaff@ajaxsearch')->name('ajax_search_counterstaff');

        //Profile
        Route::get('/system/profile', 'Profile@index')->name('profile');
        Route::post('/system/profile', 'Profile@update')->name('profileupdate');



    });
});
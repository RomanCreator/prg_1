<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');

Route::group(['middleware' => 'auth'], function() {


    /**
     * Управление правами доступа
     */
    Route::get('/home/permissions/', 'RolePermissionController@index');
    Route::get('/home/permissions/add/', 'RolePermissionController@add');
    Route::post('/home/permissions/add/', 'RolePermissionController@add');
    Route::get('/home/permissions/{id}/edit/', 'RolePermissionController@edit');
    Route::post('/home/permissions/{id}/edit/', 'RolePermissionController@edit');
    Route::delete('/home/permissions/{id}/delete/', 'RolePermissionController@delete');

    /**
     * Управление пользователями
     */
    Route::resource('/home/users', 'UserController');

    /**
     * Управление ролями перепишем вручную, так как у нас все отказывается работать
     * с не инкрементируемыми первичными ключами
     */
    //Route::resource('/home/roles/', 'RolesController');
    Route::get('/home/roles', 'RolesController@index');
    Route::get('/home/roles/create', 'RolesController@create');
    Route::post('/home/roles', 'RolesController@store');
    Route::get('/home/roles/{name_role}', 'RolesController@show');
    Route::get('/home/roles/{name_role}/edit', 'RolesController@edit');
    Route::put('/home/roles/{name_role}', 'RolesController@update');
    Route::delete('/home/roles/{name_role}', 'RolesController@destroy');

    /**
     * Управление статическими страницами сайта
     */
    Route::resource('/home/pages', 'StaticPageController');

    /**
     * Управление исследованиями
     */
    Route::resource('/home/research', 'ResearchController');
});


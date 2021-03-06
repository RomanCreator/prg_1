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

use App\Hospital;
use App\Research;


Route::post('/search', 'FrontEndController@search');

Route::get('/researchesfor/{id}', 'FrontEndController@researchesfor');

Route::get('/allresearches/', 'FrontEndController@allresearches');

Route::post('/callback_order/', 'FrontEndController@callback_order');

Route::get('/', 'FrontEndController@index');

Route::get('/hospitals', 'FrontEndController@hospitals');

Route::get('/hospitals/{id}', 'FrontEndController@hospital');

Route::get('/researches', 'FrontEndController@researches');

Route::get('/researches/{id}', 'FrontEndController@research');

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

    /**
     * Управление медицинскими учреждениями
     */
    Route::resource('/home/hospitals', 'HospitalController');

    /**
     * Управление прайс листом
     */
    Route::resource('/home/prices', 'PriceController');

    /**
     * Управление завками на обратный звонок
     */
    Route::resource('/home/callback', 'CallBackRequestController');

    /**
     * Управление районами города
     */
    Route::resource('/home/districts', 'DistrictController');

    /**
     * Управление типами исследований
     */
    Route::resource('/home/type_researches', 'TypeResearchController');

    /**
     * Управление типами томографов
     */
    Route::resource('/home/tomograph_types', 'TomographTypeController');

    /**
     * Управление номерами колл центра
     */
    Route::resource('/home/callcenternumbers', 'CallCenterPhoneNumberController');

});




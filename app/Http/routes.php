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

Route::get('/', function () {
    $hospitals = Hospital::where('status', 1)
        ->take(5)
        ->get();

    foreach ($hospitals as &$hospital) {
        /* Получим рабочее время нашего медицинского центра */
        $timeWorks = $hospital->getWeekWorksTime();
        $hospital->timeWorks = $timeWorks;
        /* Получим правильный адрес нашего медицинского центра */
        /* Так как любой адрес в нашей системе содержит название страны и города */
        /* Через запятую, то мы можем спокойно их удалить из адреса */
        $address = $hospital->address;
        $address = explode(',', $address);
        if (count($address) > 2) {
            unset($address[0]);
            unset($address[1]);
            ksort($address);
            $address = implode(', ', $address);
            $hospital->address = $address;
        }




        if (Storage::disk('public')->exists('hospitals/'.$hospital->id)) {
            if (!Storage::disk('public')->exists('hospitals/'.$hospital->id.'.derived_150x200.png')) {
                Image::make(Storage::disk('public')
                    ->get('hospitals/'.$hospital->id))
                    ->crop(150,200)
                    ->save(public_path().'/storage/hospitals/'.$hospital->id.'.derived_150x200.png');
            }

            $hospital->logo = Storage::disk('public')->url('hospitals/'.$hospital->id.'.derived_150x200.png');
            $hospital->logo .= '?'.time();
        }


    }

    $hospitalForMap = Hospital::where('status', 1)->get();
    $hospitalsData = [];
    foreach ($hospitalForMap as $hospital) {
        $timeWorks = $hospital->getWeekWorksTime();
        $address = $hospital->address;
        $address = explode(',', $address);
        if (count($address) > 2) {
            unset($address[0]);
            unset($address[1]);
            ksort($address);
            $address = implode(', ', $address);
        }


        $localData['technical_address'] = $hospital->technical_address;
        $localData['name'] = $hospital->name;
        $localData['district'] = !empty($hospital->getDistrict) ? $hospital->getDistrict->name : '';
        $localData['subway'] = $hospital->subway;
        $localData['address'] = $address;
        $localData['weekwork'] = $timeWorks;
        $hospitalsData[] = $localData;
    }

    $hospitalsData = json_encode($hospitalsData);

    return view('welcome', [
        'hospitals' => $hospitals,
        'hospitalsData' => $hospitalsData
    ]);
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

});




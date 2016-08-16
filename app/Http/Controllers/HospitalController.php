<?php

namespace App\Http\Controllers;

use App\District;
use App\Hospital;
use App\ImageStorage;
use App\TomographType;
use App\TypeResearch;
use DB;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\MessageBag;
use Image;
use Storage;
use Validator;

class HospitalController extends Controller
{
    public function __construct() {
        $this->middleware('permission:'.Hospital::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hospitals = Hospital::paginate(20);
        return view('backend.hospitals.list', ['list'=>$hospitals]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $scripts = [];
        $scripts[] = 'https://api-maps.yandex.ru/2.1/?lang=ru_RU';
        $typeResearches = TypeResearch::all();
        $tomographTypes = TomographType::all();
        $generalOrganization = Hospital::where('is_general', true)->get();
        return view ('backend.hospitals.form', [
            'districts' => District::all(),
            'typeResearches' => $typeResearches,
            'tomographTypes' => $tomographTypes,
            'nameAction' => 'Создание нового учреждения',
            'controllerPathList' => '/home/hospitals/',
            'controllerAction' => 'add',
            'controllerEntity' => new Hospital(),
            'is_general' => false,
            'generalOrganizations' => $generalOrganization,
            'scripts' => $scripts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $validator = null;

        if (isset($request->is_general) || (!isset($request->general_hospital_id) && !isset($request->is_general))) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'tags' => 'max:255',
                'description' => 'required',
                'address' => 'required',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'address' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return redirect('/home/hospitals/create/')->withInput()->withErrors($validator);
        }

        /**
         * Проверим файл на корректность
         */
        if ($request->logo && !$request->file('logo')->isValid()) {
            $message = new MessageBag(['Не корректный файл']);
            return redirect('/home/hospitals/create/')->with($message);
        }

        if (!$request->status) {
            $request->status = 0;
        } else {
            $request->status = 1;
        }

        /**
         * Создаем новое исследование
         */
        try {
            DB::transaction(function () use ($request) {
                $hospital = new Hospital();
                /* Смотрим на флаг is_general и на параметр general_hospital_id */
                /* Если установлен Флаг is_general то сохраняем то что есть */
                /* Если флаг не установлен, а передан параметр general_hospital_id */
                /* то мы инициализируем нужную клинику, и проверяем поля которые отсутствуют в данной клинике */
                if (isset($request->is_general) || ((!isset($request->general_hospital_id) || empty($request->general_hospital_id)) && !isset($request->is_general))) {
                    $hospital->name = $request->name;
                    $hospital->description = $request->description;
                    $hospital->address = $request->address;
                    $hospital->status = $request->status;
                    $hospital->district = $request->district;
                    $hospital->technical_address = $request->technical_address;
                    $hospital->tags = $request->tags;
                    $hospital->doctor_price = $request->doctor_price;
                    $hospital->save();




                    foreach ($request->type_researches as $typeId) {
                        $typeResearch = TypeResearch::find($typeId);
                        $hospital->TypeResearches()->save($typeResearch);
                    }

                    if (isset($request->tomograph_types) && !empty($request->tomograph_types)) {
                        foreach ($request->tomograph_types as $tomographId) {
                            $tomographType = TomographType::find($tomographId);
                            $hospital->TomographTypes()->save($tomographType);
                        }
                    }


                    /* Сохранение метро */
                    $technicalJSON = json_decode($request->technical_address, true);
                    if (isset($technicalJSON['stops']) && !empty($technicalJSON['stops'])) {
                        $hospital->subway = 'м. '.$technicalJSON['stops'][0]['name'].' - '.$technicalJSON['stops'][0]['distance'];
                    }
                    $hospital->weekwork = $request->worktime;
                    $hospital->type_researches_price = $request->type_researches_price;
                    $hospital->therapeutic_areas = $request->therapeutic_areas;

                    if (!empty($request->logo) && $request->file('logo')->isValid()) {
                        Storage::disk('public')->put(
                            'hospitals/'.$hospital->id,
                            file_get_contents($request->file('logo')->getRealPath())
                        );
                    }



                    if (isset($request->is_general)) {
                        $hospital->is_general = true;
                        $hospital->general_hospital_id = null;
                    } else {
                        $hospital->is_general = false;
                        $hospital->general_hospital_id = null;
                    }



                } else {
                    $GeneralHospital = Hospital::find($request->general_hospital_id);
                    if (!$GeneralHospital) {
                        throw new Exception('Не верно указана головная клиника сети');
                    }

                    /* Копируем имя, если не вставлено */
                    if (isset($request->name) && !empty($request->name)) {
                        $hospital->name = $request->name;
                    } else {
                        $hospital->name = $GeneralHospital->name;
                    }

                    if (isset($request->description) && !empty($request->description)) {
                        $hospital->description = $request->description;
                    } else {
                        $hospital->description = $GeneralHospital->description;
                    }

                    $hospital->address = $request->address;
                    $hospital->technical_address = $request->technical_address;
                    $hospital->status = $request->status;

                    if (isset($request->district) && !empty($request->district)) {
                        $hospital->district = $request->district;
                    } else {
                        $hospital->district = $GeneralHospital->district;
                    }

                    if (isset($request->tags) && !empty($request->tags)) {
                        $hospital->tags = $request->tags;
                    } else {
                        $hospital->tags = $GeneralHospital->tags;
                    }

                    if (isset($request->doctor_price) && !empty($request->doctor_price)) {
                        $hospital->doctor_price = $request->doctor_price;
                    } else {
                        $hospital->doctor_price = $GeneralHospital->doctor_price;
                    }

                    $hospital->save();

                    /* Копируем логотип если не установлен */
                    if (!empty($request->logo) && $request->file('logo')->isValid()) {
                        Storage::disk('public')->put(
                            'hospitals/'.$hospital->id,
                            file_get_contents($request->file('logo')->getRealPath())
                        );
                    } else {
                        if (Storage::disk('public')->exists('hospitals/'.$GeneralHospital->id)) {
                            Storage::disk('public')->copy('hospitals/'.$GeneralHospital->id, 'hospitals/'.$hospital->id);
                        }
                    }



                    if (isset($request->type_researches) && !empty($request->type_researches)) {
                        foreach ($request->type_researches as $typeId) {
                            $typeResearch = TypeResearch::find($typeId);
                            $hospital->TypeResearches()->save($typeResearch);
                        }
                    } else {
                        foreach ($GeneralHospital->TypeResearches as $typeResearch) {
                            $hospital->TypeResearches()->save($typeResearch);
                        }
                    }

                    if (isset($request->tomograph_types) && !empty($request->tomograph_types)) {
                        foreach ($request->tomograph_types as $tomographId) {
                            $tomographType = TomographType::find($tomographId);
                            $hospital->TomographTypes()->save($tomographType);
                        }
                    } else {
                        foreach ($GeneralHospital->TomographTypes as $tomographType) {
                            $hospital->TomographTypes()->save($tomographType);
                        }
                    }

                    /* Сохранение метро */
                    $technicalJSON = json_decode($request->technical_address, true);
                    if (isset($technicalJSON['stops']) && !empty($technicalJSON['stops'])) {
                        $hospital->subway = 'м. '.$technicalJSON['stops'][0]['name'].' - '.$technicalJSON['stops'][0]['distance'];
                    }


                    if (isset($request->worktime) && !empty($request->worktime)) {
                        $hospital->weekwork = $request->worktime;
                    } else {
                        $hospital->weekwork = $GeneralHospital->weekwork;
                    }

                    if (isset($request->type_researches_price) && !empty($request->type_researches_price)) {
                        $hospital->type_researches_price = $request->type_researches_price;
                    } else {
                        $hospital->type_researches_price = $GeneralHospital->type_researches_price;
                    }

                    if (isset($hospital->therapeutic_areas) && !empty($hospital->therapeutic_areas)) {
                        $hospital->therapeutic_areas = $request->therapeutic_areas;
                    } else {
                        $hospital->therapeutic_areas = $GeneralHospital->therapeutic_areas;
                    }

                    $hospital->is_general = false;
                    $hospital->general_hospital_id = $GeneralHospital->id;
                }

                $hospital->save();

                return true;
            });
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect('/home/hospitals/create/')->with($e->getMessage());
        }

        return redirect('/home/hospitals/')->with(['success'=>['Учреждение успешно создано']]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hospital = Hospital::find($id);

        $logo = false;

        if (Storage::disk('public')->exists('hospitals/'.$id)) {
            if (!Storage::disk('public')->exists('hospitals/'.$id.'.derived_300x300.png')) {
                Image::make(Storage::disk('public')->get('hospitals/'.$id))->crop(300,300)->save(public_path().'/storage/hospitals/'.$id.'.derived_300x300.png');
            }

            $logo = Storage::disk('public')->url('hospitals/'.$id.'.derived_300x300.png');
            $logo .= '?'.time();
        }

        $districts = District::all();
        foreach ($districts as &$district) {
            if ($district->id == $hospital->district) {
                $district->selected = 'selected';
            }
        }

        return view('backend.hospitals.view', [
            'worktime' => $hospital->weekwork,
            'districts' => $districts,
            'nameAction' => $hospital->name,
            'name' => $hospital->name,
            'logo' => $logo,
            'description' => $hospital->description,
            'status' => $hospital->status,
            'controllerPathList' => '/home/hospitals/'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $scripts = [];
        $scripts[] = 'https://api-maps.yandex.ru/2.1/?lang=ru_RU';

        $hospital = Hospital::find($id);

        $logo = false;
        if (Storage::disk('public')->exists('hospitals/'.$id)) {
            if (!Storage::disk('public')->exists('hospitals/'.$id.'.derived_300x300.png')) {
                Image::make(Storage::disk('public')->get('hospitals/'.$id))->crop(300,300)->save(public_path().'/storage/hospitals/'.$id.'.derived_300x300.png');
            }

            $logo = Storage::disk('public')->url('hospitals/'.$id.'.derived_300x300.png');
            $logo .= '?'.time();
        }

        $IM = new ImageStorage($hospital);
        $gallery = $IM->getCropped('gallery', 300, 300);

        $districts = District::all();
        foreach ($districts as &$district) {
            if ($district->id == $hospital->district) {
                $district->selected = 'selected';
            }
        }

        $typeResearches = TypeResearch::all();//$hospital->TypeResearches;
        foreach ($typeResearches as &$typeResearch) {
            foreach ($hospital->TypeResearches as $type) {
                if ($typeResearch->id === $type->id) {
                    $typeResearch->active = 'active';
                }
            }
        }

        $tomographTypes = TomographType::all();
        foreach ($tomographTypes as &$tomographType) {
            foreach ($hospital->TomographTypes as $type) {
                if ($tomographType->id === $type->id) {
                    $tomographType->active = 'active';
                }
            }
        }

        $doctorPrice = $hospital->doctor_price;

        $GeneralOrganizations = Hospital::where('is_general', true)->get();
        foreach ($GeneralOrganizations as &$generalOrganization) {
            if ($generalOrganization->id == $hospital->general_hospital_id) {
                $generalOrganization->selected = 'selected';
            }
        }

        return view ('backend.hospitals.form', [
            'districts' => $districts,
            'worktime' => $hospital->weekwork,
            'name' => $hospital->name,
            'description' => $hospital->description,
            'logo' => $logo,
            'address' => $hospital->address,
            'status' => $hospital->status,
            'technical_address' => $hospital->technical_address,
            'gallery' => $gallery,
            'tags' => $hospital->tags,
            'typeResearches' => $typeResearches,
            'doctor_price' => $doctorPrice,
            'type_researches_price'=> $hospital->type_researches_price,
            'therapeutic_areas'=> $hospital->therapeutic_areas,
            'tomographTypes' => $tomographTypes,
            'is_general' => $hospital->is_general,
            'generalOrganizations' => $GeneralOrganizations,


            'nameAction' => $hospital->name,
            'idEntity' => $hospital->id,
            'controllerPathList' => '/home/hospitals/',
            'controllerAction' => 'edit',
            'controllerEntity' => new Hospital(),
            'scripts' => $scripts
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/home/hospitals/'.$id.'/edit')->withInput()->withErrors($validator);
        }

        if ($request->logo && !$request->file('logo')->isValid()) {
            $message = new MessageBag(['Не корректный файл']);
            return redirect('/home/research/'.$id.'/edit')->with($message);
        }

        if (!$request->status) {
            $request->status = 0;
        } else {
            $request->status = 1;
        }

        $hospital = Hospital::find($id);
        $hospital->name = $request->name;
        $hospital->description = $request->description;
        $hospital->address = $request->address;
        $hospital->technical_address = $request->technical_address;
        $technicalJSON = json_decode($request->technical_address, true);
        if (isset($technicalJSON['stops']) && !empty($technicalJSON['stops'])) {
            $hospital->subway = $technicalJSON['stops'][0]['name'];
        }
        $hospital->weekwork = $request->worktime;
        $hospital->district = $request->district;
        $hospital->status = $request->status;
        $hospital->tags = $request->tags;
        $hospital->doctor_price = $request->doctor_price;
        $hospital->type_researches_price = $request->type_researches_price;
        $hospital->therapeutic_areas = $request->therapeutic_areas;



        if (isset($request->type_researches)) {
            $hospital->TypeResearches()->sync($request->type_researches);
        } else {
            $hospital->TypeResearches()->sync([]);
        }

        if (isset($request->tomograph_types)) {
            $hospital->TomographTypes()->sync($request->tomograph_types);
        } else {
            $hospital->TomographTypes()->sync([]);
        }



        if ($request->logo) {
            if (Storage::disk('public')->exists('hospitals/'.$id)) {
                Storage::disk('public')->delete('hospitals/'.$id);
            }

            if (Storage::disk('public')->exists('hospitals/'.$id.'.derived_300x300.png')) {
                Storage::disk('public')->delete('hospitals/'.$id.'.derived_300x300.png');
            }

            if (Storage::disk('public')->exists('hospitals/'.$id.'.derived_150x200.png')) {
                Storage::disk('public')->delete('hospitals/'.$id.'.derived_150x200.png');
            }

            Storage::disk('public')->put(
                'hospitals/'.$hospital->id,
                file_get_contents($request->file('logo')->getRealPath())
            );
        }

        if ($request->gallery) {
            $IS = new ImageStorage($hospital);
            $IS->save($request->gallery, 'gallery');
        }

        /* Тут банальная проверка на установку флага is_general */
        /* И корректная обработка установки этого флага и снятия другого */
        if (isset($request->is_general)) {
            /* Проверим не являлся ли раньше наш медицинский центр головной организацией? */
            if (!$hospital->is_general) {

                $GeneralHospital = $hospital->GeneralHospital();
                if ($GeneralHospital) {
                    $GeneralHospital->general_hospital_id = $hospital->id;
                    $GeneralHospital->is_general = false;
                    $GeneralHospital->save();
                    $HospitalsInWeb = Hospital::where('general_hospital_id', $GeneralHospital->id)->get();
                    foreach ($HospitalsInWeb as $hospitalInWeb) {
                        $hospitalInWeb->general_hospital_id = $hospital->id;
                        $hospitalInWeb->save();
                    }
                }

                $hospital->is_general = true;
                $hospital->general_hospital_id = null;
            }
        } else {
            if (isset($request->general_hospital_id) && !empty($request->general_hospital_id)) {
                $GeneralHospital = Hospital::find($request->general_hospital_id);
                if (!$GeneralHospital) {
                    throw new Exception('Не верно задано головное учреждение');
                }
                $hospital->general_hospital_id = $request->general_hospital_id;
            } else {
                $hospital->is_general = false;
                $hospital->general_hospital_id = null;
            }
        }

        $hospital->save();



        return redirect('/home/hospitals/'.$hospital->id.'/edit/')->with(['success'=>['Медицинское учреждение успешно обновлено!']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hospital = Hospital::find($id);
        $nameOfHospital = $hospital->name;
        $hospital->delete();
        return redirect('/home/hospitals/')->with(['success'=>['Медицинское учреждение '.$nameOfHospital.' успешно удалено!']]);
    }
}

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
        return view ('backend.hospitals.form', [
            'districts' => District::all(),
            'typeResearches' => $typeResearches,
            'tomographTypes' => $tomographTypes,
            'nameAction' => 'Создание нового учреждения',
            'controllerPathList' => '/home/hospitals/',
            'controllerAction' => 'add',
            'controllerEntity' => new Hospital(),
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'tags' => 'max:255',
            'description' => 'required',
            'address' => 'required',
        ]);

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
                $hospital->name = $request->name;
                $hospital->description = $request->description;
                $hospital->address = $request->address;
                $hospital->status = $request->status;
                $hospital->district = $request->district;
                $hospital->technical_address = $request->technical_address;
                $hospital->tags = $request->tags;
                $hospital->doctor_price = $request->doctor_price;
                foreach ($request->type_researches as $typeId) {
                    $typeResearch = TypeResearch::find($typeId);
                    $hospital->TypeResearches()->save($typeResearch);
                }

                foreach ($request->tomograph_types as $tomographId) {
                    $tomographType = TomographType::find($tomographId);
                    $hospital->TomographTypes()->save($tomographType);
                }
                /* Сохранение метро */
                $technicalJSON = json_decode($request->technical_address, true);
                if (isset($technicalJSON['stops']) && !empty($technicalJSON['stops'])) {
                    $hospital->subway = $technicalJSON['stops'][0]['name'];
                }
                $hospital->weekwork = $request->worktime;
                $hospital->type_researches_price = $request->type_researches_price;
                $hospital->therapeutic_areas = $request->therapeutic_areas;
                $hospital->save();
                if (!empty($request->logo) && $request->file('logo')->isValid()) {
                    Storage::disk('public')->put(
                        'hospitals/'.$hospital->id,
                        file_get_contents($request->file('logo')->getRealPath())
                    );
                }
                return true;
            });
        } catch (Exception $e) {
            $message = new MessageBag([$e->getMessage()]);
            return redirect('/home/hospitals/create/')->with($message);
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

        $hospital->TypeResearches()->sync($request->type_researches);
        $hospital->TomographTypes()->sync($request->tomograph_types);

        $hospital->save();

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

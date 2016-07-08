<?php

namespace App\Http\Controllers;

use App\Hospital;
use DB;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\MessageBag;
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
        return view ('backend.hospitals.form', [
            'nameAction' => 'Создание нового учреждения',
            'controllerPathList' => '/home/hospitals/',
            'controllerAction' => 'add',
            'controllerEntity' => new Hospital()
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
            'description' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/home/hospitals/create/')->withInput()->withErrors($validator);
        }

        /**
         * Проверим файл на корректность
         */
        if ($request->diagram && !$request->file('logo')->isValid()) {
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
                $hospital->save();
                if (!empty($request->diagram) && $request->file('logo')->isValid()) {
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
            $urlToImg = Storage::disk('public')->url('hospitals/'.$id);
            $logo = $urlToImg;//Image::make($urlToImg);
        }

        return view('backend.hospitals.view', [
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
        $hospital = Hospital::find($id);

        $logo = false;
        if (Storage::disk('public')->exists('hospitals/'.$id)) {
            $logo = Storage::disk('public')->url('hospitals/'.$id);
            $logo .= '?'.time();
        }

        return view ('backend.hospitals.form', [
            'name' => $hospital->name,
            'description' => $hospital->description,
            'logo' => $logo,
            'address' => $hospital->address,
            'status' => $hospital->status,

            'nameAction' => $hospital->name,
            'idEntity' => $hospital->id,
            'controllerPathList' => '/home/hospitals/',
            'controllerAction' => 'edit',
            'controllerEntity' => new Hospital()
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
        $hospital->status = $request->status;
        $hospital->save();

        if ($request->logo) {
            if (Storage::disk('public')->exists('hospitals/'.$id)) {
                Storage::disk('public')->delete('hospitals/'.$id);
            }

            Storage::disk('public')->put(
                'hospitals/'.$hospital->id,
                file_get_contents($request->file('logo')->getRealPath())
            );
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

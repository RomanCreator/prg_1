<?php

namespace App\Http\Controllers;

use App\District;
use DB;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\MessageBag;
use Validator;

class DistrictController extends Controller
{
    public function __construct() {
        $this->middleware('permission:'.District::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $districts = District::paginate(20);
        return view('backend.districts.list', ['list'=>$districts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('backend.districts.form', [
            'nameAction' => 'Новый район города',
            'controllerPathList' => '/home/districts/',
            'controllerAction' => 'add',
            'controllerEntity' => new District()
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
            'name' => 'required|unique:districts|max:255',
        ]);


        if ($validator->fails()) {
            return redirect('/home/districts/create/')->withInput()->withErrors($validator);
        }

        try {
            DB::transaction (function () use ($request) {
                $district = new District();
                $district->name = $request->name;
                $district->save();
                return true;
            });
        } catch (Exception $e) {
            $message = new MessageBag([$e->getMessage()]);
            dd($e->getMessage());
            return redirect('/home/districts/create/')->with($message);
        }

        return redirect('/home/districts/')->with(['success'=>['Район города добавлен успешно']]);

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $district = District::find($id);

        return view('backend.districts.view', [
            'nameAction' => $district->name,
            'name' => $district->name,
            'controllerPathList' => '/home/districts/'
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
        $district = District::find($id);
        return view ('backend.districts.form', [
            'name' => $district->name,

            'nameAction' => $district->name,
            'idEntity' => $district->id,
            'controllerPathList' => '/home/districts/',
            'controllerAction' => 'edit',
            'controllerEntity' => new District(),
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
            'name' => 'required|unique:districts|max:255',
        ]);


        if ($validator->fails()) {
            return redirect('/home/districts/'.$id.'/edit/')->withInput()->withErrors($validator);
        }

        $district = District::find($id);
        $district->name = $request->name;
        $district->save();

        return redirect('/home/districts/'.$district->id.'/edit/')->with(['success'=>['Район успешно изменен!']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $district = District::find($id);
        $nameOfDistrict = $district->name;
        $district->delete();
        return redirect('/home/districts/')->with(['success'=>[$nameOfDistrict.' успешно удален!']]);
    }
}

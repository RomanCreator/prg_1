<?php

namespace App\Http\Controllers;

use App\TomographType;
use DB;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\MessageBag;
use Validator;

class TomographTypeController extends Controller
{
    public function __construct() {
        $this->middleware('permission:'.TomographType::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tomographType = TomographType::paginate(20);
        return view('backend.tomograph_types.list', ['list'=>$tomographType]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $scripts = [];

        return view ('backend.tomograph_types.form', [
            'nameAction' => 'Создание нового типа томографа',
            'controllerPathList' => '/home/tomograph_types/',
            'controllerAction' => 'add',
            'controllerEntity' => new TomographType(),
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
        ]);

        if ($validator->fails()) {
            return redirect('/home/tomograph_types/create/')->withInput()->withErrors($validator);
        }

        try {
            DB::transaction(function () use ($request) {
                $tomographType = new TomographType();
                $tomographType->name = $request->name;
                $tomographType->save();
                return true;
            });
        } catch (Exception $e) {
            $message = new MessageBag([$e->getMessage()]);
            return redirect('/home/tomograph_types/create/')->with($message);
        }

        return redirect('/home/tomograph_types/')->with(['success'=>['Новый тип томографа создан']]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tomographType = TomographType::find($id);

        return view ('backend.tomograph_types.view', [
            'nameAction' => $tomographType->name,
            'name' => $tomographType->name,
            'controllerPathList' => '/home/tomograph_types/'
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
        $tomographType = TomographType::find($id);

        return view ('backend.tomograph_types.form', [
            'name' => $tomographType->name,

            'nameAction' => $tomographType->name,
            'idEntity' => $tomographType->id,
            'controllerPathList' => '/home/tomograph_types/',
            'controllerAction' => 'edit',
            'controllerEntity' => new TomographType()
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
        ]);

        if ($validator->fails()) {
            return redirect('/home/tomograph_types/'.$id.'/edit')->withInput()->withErrors($validator);
        }

        $tomographType = TomographType::find($id);
        $tomographType->name = $request->name;
        $tomographType->save();

        return redirect('/home/tomograph_types/'.$tomographType->id.'/edit/')->with(['success'=>['Тип томографа успешно изменен!']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tomographType = TomographType::find($id);
        $name = $tomographType->name;
        $tomographType->delete();
        return redirect('/home/type_researches/')->with(['success'=>['Тип томографа '.$name.' успешно удален!']]);
    }
}

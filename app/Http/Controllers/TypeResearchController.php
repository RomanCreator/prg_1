<?php

namespace App\Http\Controllers;

use App\TypeResearch;
use DB;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\MessageBag;
use Mockery\Matcher\Type;
use Validator;

class TypeResearchController extends Controller
{
    public function __construct() {
        $this->middleware('permission:'.TypeResearch::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $typeResearches = TypeResearch::paginate(20);
        return view('backend.type_researches.list', ['list'=>$typeResearches]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $scripts = [];

        return view ('backend.type_researches.form', [
            'nameAction' => 'Создание нового типа исследования',
            'controllerPathList' => '/home/type_researches/',
            'controllerAction' => 'add',
            'controllerEntity' => new TypeResearch(),
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
            return redirect('/home/type_researches/create/')->withInput()->withErrors($validator);
        }

        try {
            DB::transaction(function () use ($request) {
                $typeResearch = new TypeResearch();
                $typeResearch->name = $request->name;
                $typeResearch->save();
                return true;
            });
        } catch (Exception $e) {
            $message = new MessageBag([$e->getMessage()]);
            return redirect('/home/type_researches/create/')->with($message);
        }

        return redirect('/home/type_researches/')->with(['success'=>['Новый тип учреждения создан']]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $typeResearch = TypeResearch::find($id);

        return view ('backend.type_researches.view', [
            'nameAction' => $typeResearch->name,
            'name' => $typeResearch->name,
            'controllerPathList' => '/home/type_researches/'
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
        $typeResearch = TypeResearch::find($id);

        return view ('backend.type_researches.form', [
            'name' => $typeResearch->name,

            'nameAction' => $typeResearch->name,
            'idEntity' => $typeResearch->id,
            'controllerPathList' => '/home/type_researches/',
            'controllerAction' => 'edit',
            'controllerEntity' => new TypeResearch()
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
            return redirect('/home/type_researches/'.$id.'/edit')->withInput()->withErrors($validator);
        }

        $typeResearch = TypeResearch::find($id);
        $typeResearch->name = $request->name;
        $typeResearch->save();

        return redirect('/home/type_researches/'.$typeResearch->id.'/edit/')->with(['success'=>['Тип исследования успешно изменен!']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $typeResearch = TypeResearch::find($id);
        $name = $typeResearch->name;
        $typeResearch->delete();
        return redirect('/home/type_researches/')->with(['success'=>['Тип исследования '.$name.' успешно удален!']]);
    }
}

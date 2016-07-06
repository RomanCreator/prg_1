<?php

namespace App\Http\Controllers;

use App\Hospital;
use App\Price;
use App\Research;
use DB;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\MessageBag;
use Validator;

class PriceController extends Controller
{

    public function __construct() {
        $this->middleware('permission:'.Price::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prices = Price::paginate(20);
        return view('backend.prices.list', ['list'=>$prices]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hospitals = Hospital::all();
        $researches = Research::all();
        return view ('backend.prices.form', [
            'hospitals' => $hospitals,
            'researches' => $researches,
            'nameAction' => 'Создание нового пункта прайс-листа',
            'controllerPathList' => '/home/prices/',
            'controllerAction' => 'add',
            'controllerEntity' => new Price()
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
            'hospital_id' => 'required|integer|exists:hospitals,id',
            'research_id' => 'required|integer|exists:researches,id',
            'price_from' => 'required|numeric',
            'price_to' => 'numeric',
        ]);

        if ($validator->fails()) {
            return redirect('/home/prices/create/')->withInput()->withErrors($validator);
        }

        if (!$request->status) {
            $request->status = 0;
        } else {
            $request->status = 1;
        }

        if (empty($request->description)) {
            $request->description = null;
        }

        /**
         * Создаем новое исследование
         */
        try {
            DB::transaction(function () use ($request) {
                $price = new Price();
                $price->hospital_id = $request->hospital_id;
                $price->research_id = $request->research_id;
                $price->price_from = $request->price_from;
                $price->price_to = $request->price_to;
                $price->description = $request->description;
                $price->status = $request->status;
                $price->save();

                return true;
            });
        } catch (Exception $e) {
            $message = new MessageBag([$e->getMessage()]);
            return redirect('/home/prices/create/')->with($message);
        }

        return redirect('/home/prices/')->with(['success'=>['Элемент прайс-листа успешно создан']]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $price = Price::find($id);
        if (is_null($price->description)) {
            $price->description = $price->research->description;
        }

        $hospital = $price->hospital;
        $research = $price->research;

        if (is_null($price->description) || empty($price->description)) {
            $price->description = $research->description;
        }

        return view('backend.prices.view', [
            'hospital'=> $hospital,
            'research'=> $research,
            'price_from' => $price->price_from,
            'price_to' => $price->price_to,
            'description' => $price->description,
            'status' => $price->status,


            'nameAction' => 'Элемент прайс листа для учреждения "'.$price->hospital->name.'" и исследования "'.$price->research->name.'"',
            'status' => $price->status,
            'controllerPathList' => '/home/prices/'
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
        $price = Price::find($id);
        $priceHospital = $price->hospital;
        $priceResearch = $price->research;

        $hospitals = Hospital::all();
        foreach ($hospitals as &$hospital) {
            if ($hospital->id == $priceHospital->id) {
                $hospital->selected = 'selected';
            }
        }

        $researches = Research::all();
        foreach ($researches as &$research) {
            if ($research->id == $priceResearch->id) {
                $research->selected = 'selected';
            }
        }



        return view ('backend.prices.form', [
            'hospitals' => $hospitals,
            'researches' => $researches,
            'price_from' => $price->price_from,
            'price_to' => $price->price_to,
            'description' => $price->description,
            'status' => $price->status,

            'nameAction' => 'Учреждение "'.$price->hospital->name.'" исследование "'.$price->research->name.'"',
            'idEntity' => $price->id,
            'controllerPathList' => '/home/prices/',
            'controllerAction' => 'edit',
            'controllerEntity' => new Price()
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
            'hospital_id' => 'required|integer|exists:hospitals,id',
            'research_id' => 'required|integer|exists:researches,id',
            'price_from' => 'required|numeric',
            'price_to' => 'numeric',
        ]);

        if ($validator->fails()) {
            return redirect('/home/prices/'.$id.'/edit')->withInput()->withErrors($validator);
        }

        if (!$request->status) {
            $request->status = 0;
        } else {
            $request->status = 1;
        }

        if (empty($request->description)) {
            $request->description = null;
        }

        $price = Price::find($id);
        $price->hospital_id = $request->hospital_id;
        $price->research_id = $request->research_id;
        $price->price_from = $request->price_from;

        if ($request->price_to == 0) {
            $price->price_to = NULL;
        } else {
            $price->price_to = $request->price_to;
        }
        $price->description = $request->description;
        $price->status = $request->status;
        $price->save();

        return redirect('/home/prices/'.$price->id.'/edit/')->with(['success'=>['Элемент прайс-листа успешно обновлен!']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $price = Price::find($id);
        $priceHospital = $price->hospital->name;
        $priceResearch = $price->research->name;
        $price->delete();
        return redirect('/home/prices/')->with(['success'=>['Элемент прайс листа для учреждения "'.$priceHospital.'" и исследования "'.$priceResearch.'" успешно удалено!']]);
    }
}

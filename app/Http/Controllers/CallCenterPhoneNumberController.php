<?php

namespace App\Http\Controllers;

use App\CallCenterPhoneNumber;
use DB;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\MessageBag;
use Validator;

class CallCenterPhoneNumberController extends Controller
{
    public function __construct() {
        $this->middleware('permission:'.CallCenterPhoneNumber::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pohones = CallCenterPhoneNumber::paginate(20);
        return view('backend.callcenterphonenumber.list', ['list'=>$pohones]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.callcenterphonenumber.form',[
            'nameAction' => 'Новый телефон колл центра',
            'controllerPathList' => '/home/callcenternumbers/',
            'controllerAction' => 'add',
            'controllerEntity' => new CallCenterPhoneNumber()
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
            'number' => 'required|unique:call_center_phone_numbers|max:255'
        ]);

        if ($validator->fails()) {
            return redirect('/home/callcenternumbers/create/')->withInput()->withErrors($validator);
        }

        try {
            DB::transaction (function () use ($request) {
                $callCenterNumber = new CallCenterPhoneNumber();
                $callCenterNumber->number = $request->number;
                $callCenterNumber->save();
                return true;
            });
        } catch (Exception $e) {
            $message = new MessageBag([$e->getMessage()]);
            dd($e->getMessage());
            return redirect('/home/callcenternumbers/create/')->with($message);
        }

        return redirect('/home/callcenternumbers/')->with(['success'=>['Номер колл центра успешно добавлен']]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $callCenterNumber = CallCenterPhoneNumber::find($id);
        return view('backend.callcenterphonenumber.view', [
            'nameAction' => $callCenterNumber->number,
            'number' => $callCenterNumber->number,
            'controllerPathList' => '/home/callcenternumbers/'
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
        $callCenterNumber = CallCenterPhoneNumber::find($id);
        return view('backend.callcenterphonenumber.form', [
            'number' => $callCenterNumber->number,

            'nameAction' => $callCenterNumber->number,
            'idEntity' => $callCenterNumber->id,
            'controllerPathList' => '/home/callcenternumbers/',
            'controllerAction' => 'edit',
            'controllerEntity' => new CallCenterPhoneNumber(),
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
            'number' => 'required|unique:call_center_phone_numbers|max:255'
        ]);


        if ($validator->fails()) {
            return redirect('/home/callcenternumbers/create/')->withInput()->withErrors($validator);
        }


        $callCenterNumber = CallCenterPhoneNumber::find($id);
        $callCenterNumber->number = $request->number;
        $callCenterNumber->save();

        return redirect('/home/callcenternumbers/'.$callCenterNumber->id.'/edit/')->with(['success'=>['Телефон Центра МРТ успешно изменен!']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $callCenterNumber = CallCenterPhoneNumber::find($id);
        $nameOfNumber = $callCenterNumber->number;
        $callCenterNumber->delete();
        return redirect('/home/callcenternumbers/')->with(['success'=>[$nameOfNumber.' успешно удален!']]);
    }
}

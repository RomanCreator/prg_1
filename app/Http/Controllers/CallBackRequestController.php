<?php

namespace App\Http\Controllers;

use App\CallBackRequest;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\MessageBag;

class CallBackRequestController extends Controller
{
    public function __construct() {
        $this->middleware('permission:'.CallBackRequest::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $callbackRequests = CallBackRequest::paginate(20);
        return view('backend.callback.list', [
            'list'=>$callbackRequests
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function create()
    {
        throw new Exception('Невозможно создать заявку вручную.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function store(Request $request)
    {
        throw new Exception('Невозможно сохранить новую заявку созданную вручную.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $callBackRequest = CallBackRequest::find($id);
        $comments = $callBackRequest->getComments();
        $research = $callBackRequest->getResearch();
        $name = $callBackRequest->name;
        $phone = $callBackRequest->phone;
        $message = $callBackRequest->message;
        $status = $callBackRequest->getNameOfCurrentStatus();
        $hospital = $callBackRequest->getHospital();

        return view('backend.callback.view', [
            'name' => $name,
            'phone' => $phone,
            'research' => $research,
            'message' => $message,
            'status' => $status,
            'comments' => $comments,
            'hospital' => $hospital,


            'nameAction' => $name,
            'controllerPathList' => '/home/callback/'
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
        $callBackRequest = CallBackRequest::find($id);
        $comments = $callBackRequest->getComments();
        $research = $callBackRequest->getResearch();
        $name = $callBackRequest->name;
        $phone = $callBackRequest->phone;
        $message = $callBackRequest->message;
        $status = $callBackRequest->getNameOfCurrentStatus();
        $hospital = $callBackRequest->getHospital();
        $allowedStatus = $callBackRequest->getAllowedStatus();


        return view('backend.callback.form', [
            'name' => $name,
            'phone' => $phone,
            'research' => $research,
            'message' => $message,
            'status' => $status,
            'comments' => $comments,
            'hospital' => $hospital,
            'allowedStatus' => $allowedStatus,


            'nameAction' => $name,
            'controllerPathList' => '/home/callback/',
            'idEntity' => $callBackRequest->id,
            'controllerAction' => 'edit',
            'controllerEntity' => new CallBackRequest(),
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
        $callBack = CallBackRequest::find($id);

        if ($request->comment) {
            $callBack->addComment($request->comment);
        }

        if ($request->status) {
            $request->status = 0 + $request->status;
            if (is_int($request->status)) {
                if (!$callBack->changeStatusTo($request->status)) {
                    return redirect('/home/callback/'.$callBack->id.'/edit')->with(['error' => 'Невозможно перевести заявку в указанный статус']);
                }
            }
        }

        $callBack->save();
        return redirect('/home/callback/'.$callBack->id.'/edit/')->with(['success'=>['Заявка на обратный звонок успешно обновлена!']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $callback = CallBackRequest::find($id);
        $nameOfResearch = $callback->name;
        $callback->delete();
        return redirect('/home/callback/')->with(['success'=>['Заявка на обратный звонок от '.$nameOfResearch.' успешно удалена!']]);
    }
}

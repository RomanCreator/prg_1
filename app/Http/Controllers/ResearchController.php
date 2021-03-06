<?php

namespace App\Http\Controllers;

use App\ImageStorage;
use App\Research;
use DB;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Image;
use Validator;

class ResearchController extends Controller
{

    public function __construct() {

        $this->middleware('permission:'.Research::class);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $researches = Research::paginate(20);
        return view('backend.research.list', ['list'=>$researches]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.research.form', [
            'nameAction' => 'Создание нового исследования',
            'controllerPathList' => '/home/research/',
            'controllerAction' => 'add',
            'controllerEntity' => new Research(),
            'showState' => false
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
            return redirect('/home/research/create/')->withInput()->withErrors($validator);
        }

        /**
         * Проверим файл на корректность
         */
        if ($request->diagram && !$request->file('diagram')->isValid()) {
            $message = new MessageBag(['Не корректный файл']);
            return redirect('/home/research/create/')->with($message);
        }

        if (!$request->state) {
            $request->state = 0;
        } else {
            $request->state = 1;
        }

        /**
         * Создаем новое исследование
         */
        try {
            DB::transaction(function () use ($request) {
                $research = new Research();
                $research->name = $request->name;
                $research->description = $request->description;
                $research->state = $request->state;
                $research->show_state = $request->show_state;
                $research->show_position = $request->show_position;
                $research->lead = $request->lead;
                $research->save();
                if (!empty($request->diagram) && $request->file('diagram')->isValid()) {
                    Storage::disk('public')->put(
                        'researches/'.$research->id,
                        file_get_contents($request->file('diagram')->getRealPath())
                    );
                }
                return true;
            });
        } catch (Exception $e) {
            $message = new MessageBag([$e->getMessage()]);
            return redirect('/home/research/create/')->with($message);
        }

        return redirect('/home/research/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $research = Research::find($id);

        $diagram = false;

        if (Storage::disk('public')->exists('researches/'.$id)) {
            if (!Storage::disk('public')->exists('researches/'.$id.'.derived_300x300.png')) {
                Image::make(Storage::disk('public')->get('researches/'.$id))->crop(300,300)->save(public_path().'/storage/researches/'.$id.'.derived_300x300.png');
            }

            $diagram = Storage::disk('public')->url('researches/'.$id.'.derived_300x300.png');
            $diagram .= '?'.time();
        }

        return view ('backend.research.view', [
            'nameAction' => $research->name,
            'name' => $research->name,
            'diagram' => $diagram,
            'description' => $research->description,
            'status' => $research->status,
            'controllerPathList' => '/home/research/',
            'showState' => $research->show_state,
            'show_position' => $research->show_position,
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
        $research = Research::find($id);

        $diagram = false;

        if (Storage::disk('public')->exists('researches/'.$id)) {
            if (!Storage::disk('public')->exists('researches/'.$id.'.derived_300x300.png')) {
                Image::make(Storage::disk('public')->get('researches/'.$id))->crop(300,300)->save(public_path().'/storage/researches/'.$id.'.derived_300x300.png');
            }

            $diagram = Storage::disk('public')->url('researches/'.$id.'.derived_300x300.png');
            $diagram .= '?'.time();
        }

        $IM = new ImageStorage($research);
        $gallery = $IM->getCropped('gallery', 300, 300);
        $gallerySrc = $IM->getOrigImage('gallery', true);

        return view ('backend.research.form', [
            'name' => $research->name,
            'diagram' => $diagram,
            'description' => $research->description,
            'state' => $research->state,
            'gallery' => $gallery,
            'gallerySrc' => $gallerySrc,
            'show_position' => $research->show_position,
            'lead' => $research->lead,

            'nameAction' => $research->name,
            'idEntity' => $research->id,
            'controllerPathList' => '/home/research/',
            'controllerAction' => 'edit',
            'controllerEntity' => new Research(),
            'showState' => $research->show_state,
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
            'show_position' => 'integer',
        ]);

        if ($validator->fails()) {
            return redirect('/home/research/'.$id.'/edit')->withInput()->withErrors($validator);
        }

        if ($request->diagram && !$request->file('diagram')->isValid()) {
            $message = new MessageBag(['Не корректный файл']);
            return redirect('/home/research/'.$id.'/edit')->with($message);
        }

        if (!$request->state) {
            $request->state = 0;
        } else {
            $request->state = 1;
        }

        $research = Research::find($id);
        $research->name = $request->name;
        $research->description = $request->description;
        $research->state = $request->state;
        $research->show_position = $request->show_position;
        $research->lead = $request->lead;

        if (!$request->show_state) {
            $research->show_state = 0;
        } else {
            $research->show_state = 1;
        }

        $research->save();

        if ($request->gallery) {
            $IS = new ImageStorage($research);
            $IS->save($request->gallery, 'gallery');
        }

        if (!empty($request->deletediagram) && Storage::disk('public')->exists('researches/'.$id)) {
            if (Storage::disk('public')->exists('researches/'.$id)) {
                Storage::disk('public')->delete('researches/'.$id);
            }

            if (Storage::disk('public')->exists('researches/'.$id.'.derived_300x300.png')) {
                Storage::disk('public')->delete('researches/'.$id.'.derived_300x300.png');
            }

            if (Storage::disk('public')->exists('hospitals/'.$id.'.derived_150x200.png')) {
                Storage::disk('public')->delete('hospitals/'.$id.'.derived_150x200.png');
            }
        }

        if (!empty($request->diagram) && $request->file('diagram')->isValid() && empty($request->deletediagram)) {
            if (Storage::disk('public')->exists('researches/'.$id)) {
                Storage::disk('public')->delete('researches/'.$id);
            }

            if (Storage::disk('public')->exists('researches/'.$id.'.derived_300x300.png')) {
                Storage::disk('public')->delete('researches/'.$id.'.derived_300x300.png');
            }

            if (Storage::disk('public')->exists('hospitals/'.$id.'.derived_150x200.png')) {
                Storage::disk('public')->delete('hospitals/'.$id.'.derived_150x200.png');
            }

            Storage::disk('public')->put(
                'researches/'.$research->id,
                file_get_contents($request->file('diagram')->getRealPath())
            );
        }

        return redirect('/home/research/'.$research->id.'/edit/')->with(['success'=>['Исследование успешно изменено!']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $research = Research::find($id);
        $nameOfResearch = $research->name;
        $research->delete();
        return redirect('/home/research/')->with(['success'=>['Исследование '.$nameOfResearch.' успешно удалено!']]);
    }
}

<?php

namespace App\Http\Controllers;

use App\StaticPage;
use DB;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;

class StaticPageController extends Controller
{
    public function __construct() {
        $this->middleware('permission:'.StaticPage::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = StaticPage::paginate(20);
        return view('backend.pages.list', [
            'list' => $pages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.pages.form', [
            'nameAction' => 'Создание новой статической страницы',
            'controllerPathList' => '/home/pages/',
            'controllerAction' => 'add',
            'controllerEntity' => new StaticPage()
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
            'path' => 'required|max:255|unique:static_pages,path',
            'title' => 'max:255',
            'keywords' => 'max:255',
            'description' => 'max:255'
        ]);

        if ($validator->fails()) {
            return redirect('/home/pages/create/')->withInput()->withErrors($validator);
        }

        try {
            DB::transaction(function () use ($request) {
                /* тут присваиваем и сохраняем */
                $page = new StaticPage();
                $page->path = $request->path;
                $page->title = $request->title;
                $page->keywords = $request->keywords;
                $page->description = $request->description;
                $page->content = $request->content;

                /* так как тут ручное сохранение, то отсальные поля нас не интересуют */
                $page->save();

                return true;
            });
        } catch (Exception $e) {
            $message = new MessageBag([$e->getMessage()]);
            return redirect('/home/pages/create/')->with($message);
        }

        return redirect('/home/pages/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = StaticPage::find($id);

        return view('backend.pages.view', [
            'nameAction' => isset($page->title) ? $page->title : $page->path,
            'path' => $page->path,
            'title' => $page->title,
            'keywords' => $page->keywords,
            'description' => $page->description,
            'content' => $page->content,
            'created_at' => $page->created_at,
            'updated_at' => $page->updated_at,
            'controllerPathList' => '/home/pages/'
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
        $page = StaticPage::find($id);

        return view('backend.pages.form', [
            'nameAction' => isset($page->title) ? $page->title : $page->path,
            'path' => $page->path,
            'title' => $page->title,
            'keywords' => $page->keywords,
            'description' => $page->description,
            'content' => $page->content,
            'idEntity' => $page->id,
            'created_at' => $page->created_at,
            'updated_at' => $page->updated_at,
            'controllerPathList' => '/home/pages/',
            'controllerAction' => 'edit',
            'controllerEntity' => new StaticPage()
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
            'path' => 'required|max:255',
            'title' => 'max:255',
            'keywords' => 'max:255',
            'description' => 'max:255'
        ]);

        if ($validator->fails()) {
            return redirect('/home/pages/'.$id.'/edit/')->withInput()->withErrors($validator);
        }

        $pageOfPath = StaticPage::where(['path'=>$request->path])->first();
        if ($pageOfPath && $pageOfPath->id != $id) {
            $message = new MessageBag(['Страница с таким путем уже существует']);
            return redirect('/home/pages/'.$id.'/edit/')->with($message);
        }

        $page = StaticPage::find($id);
        $page->path = $request->path;
        $page->title = $request->title;
        $page->keywords = $request->keywords;
        $page->description = $request->description;
        $page->content = $request->content;
        $page->save();

        return redirect('/home/pages/'.$page->id.'/edit/')->with(['success'=>['Данные страницы успешно изменены.']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = StaticPage::find($id);
        $nameOfPage = isset($page->title) ? $page->title : $page->path;
        $page->delete();
        return redirect('/home/pages/')->with(['success'=>['Страница '.$nameOfPage.' успешно удалена!']]);
    }
}

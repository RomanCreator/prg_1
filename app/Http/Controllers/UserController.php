<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->cannot('index', new User())) {
            abort(403, 'Доступ запрещен');
        }

        $users = User::paginate(20);
        return view('backend.users.list', ['list'=>$users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->cannot('add', new User())) {
            abort(403, 'Доступ запрещен');
        }

        $roles = Role::all();

        return view('backend.users.form', ['nameAction' => 'Создание нового пользователя',
            'roles' => $roles,
            'controllerPathList' => '/home/users/',
            'controllerAction' => 'add',
            'controllerEntity' => new User()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->cannot('add', new User())) {
            abort(403, 'Доступ запрещен');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'surname' => 'max:255',
            'middlename' => 'max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|max:255',
            'repassword' => 'required|confirmed|max:255'
        ]);

        if ($validator->fails()) {
            return redirect('/home/users/create/')->withInput()->withErrors($validator);
        }

        /**
         * Проверяем введенный E-mail на наличие такого Email в базе
         */
        $user = User::find(['email'=>$request->email]);
        if ($user) {
            $message = new MessageBag(['Пользователь с таким email уже существует']);
            return redirect('/home/users/create/')->withInput()->withErrors($message);
        }

        /**
         * Создаем пользователя
         */
        $user = new User();
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->middlename = $request->middlename;
        $user->email = $request->email;
        //$user->password =
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

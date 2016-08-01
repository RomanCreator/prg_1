<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class StaticPageFrontendController extends Controller
{
    public function showPage($page) {
        dd('ураааа есть такая страница');
        return view('welcome');
    }
}

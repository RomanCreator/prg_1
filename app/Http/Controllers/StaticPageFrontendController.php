<?php

namespace App\Http\Controllers;

use App\Research;
use Illuminate\Http\Request;

use App\Http\Requests;

class StaticPageFrontendController extends Controller
{
    public function showPage($page) {
        $researches = Research::where('state', 1)->where('show_state', 1)->orderBy('show_position', 'asc')->get();
        $researches->sortBy('show_position');

        return view('static', [
            'keywords' => $page->keywords,
            'description' => $page->description,
            'title' => $page->title,
            'researches' => $researches,
            'content' => $page->content
        ]);
    }
}

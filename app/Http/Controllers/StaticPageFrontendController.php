<?php

namespace App\Http\Controllers;

use App\Research;
use Illuminate\Http\Request;

use App\Http\Requests;

class StaticPageFrontendController extends Controller
{
    public function showPage($page) {
        $research = Research::where('state', 1)->take(10)->get();

        return view('static', [
            'keywords' => $page->keywords,
            'description' => $page->description,
            'title' => $page->title,
            'researches' => $research,
            'content' => $page->content
        ]);
    }
}

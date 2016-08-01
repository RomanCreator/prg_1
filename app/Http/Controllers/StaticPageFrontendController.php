<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class StaticPageFrontendController extends Controller
{
    public function showPage($page) {
        return view('static', [
            'keywords' => $page->keywords,
            'description' => $page->description,
            'title' => $page->title,
            'content' => $page->content
        ]);
    }
}

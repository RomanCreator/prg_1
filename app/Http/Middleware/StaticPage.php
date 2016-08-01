<?php

namespace App\Http\Middleware;

use App\Http\Controllers\StaticPageController;
use App\StaticPage as StaticPageModel;
use Closure;
use Route;

class StaticPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() == 'GET') {
            $page = StaticPageModel::where('path', $request->path());
            if ($page && $page->count() != 0) {
                $page = $page->first();
                Route::get('/'.$request->path().'/', function () use($page) {
                    $Controller = new \App\Http\Controllers\StaticPageFrontendController();
                    $Controller->showPage($page);
                });
            }
        }
        return $next($request);
    }
}

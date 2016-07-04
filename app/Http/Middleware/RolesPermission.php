<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class RolesPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $Entity = new $role();

        if ($request->isMethod('GET')) {
            if ($request->is('/home/*/create')) {
                //echo 'создание';
                if (Auth::user()->cannot('add', $Entity)) {
                    abort(403, 'Доступ запрещен');
                }
            } elseif ($request->is('/home/*/edit')) {
                //echo 'редактирование';
                if (Auth::user()->cannot('edit', $Entity)) {
                    abort(403, 'Доступ запрещен');
                }
            } elseif ($request->is('/home/*/(^\d+$)')) {
                if (Auth::user()->cannot('view', $Entity)) {
                    abort(403, 'Доступ запрещен');
                }
            } else {
                if (Auth::user()->cannot('index', $Entity)) {
                    abort(403, 'Доступ запрещен');
                }
            }
        }

        if ($request->isMethod('POST')) {
            if ($request->is('/home/*')) {
                //echo 'создание';
                if (Auth::user()->cannot('add', $Entity)) {
                    abort(403, 'Доступ запрещен');
                }
            }
        }

        if ($request->isMethod('PUT')) {
            if ($request->is('/home/*/(^\d+$)')) {
                //echo 'обновление';
                if (Auth::user()->cannot('edit', $Entity)) {
                    abort(403, 'Доступ запрещен');
                }
            }
        }

        if ($request->isMethod('DELETE')) {
            if ($request->is('/home/*/(^\d+$)')) {
                //echo 'удаление';
                if (Auth::user()->cannot('delete', $Entity)) {
                    abort(403, 'Доступ запрещен');
                }
            }
        }


        return $next($request);
    }
}

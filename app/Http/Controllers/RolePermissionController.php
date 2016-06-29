<?php

namespace App\Http\Controllers;

use App\RolePermission;
use Illuminate\Http\Request;

use App\Http\Requests;

class RolePermissionController extends Controller
{
    //
    /**
     * Показывает доступные сущности и права на них
     */
    public function index() {
        $rolePermissions = RolePermission::paginate(20);
        return view('permissions.list', ['list'=>$rolePermissions]);
    }
}

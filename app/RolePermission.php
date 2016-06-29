<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'role_permission';
    public $timestamps = false;

    protected $fillable=['rp_role_name', 'rp_entity_name', 'rp_action'];
}

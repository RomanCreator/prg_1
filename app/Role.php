<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    public $incrementing = false;
    public $primaryKey = 'name_role';

    protected $fillable = ['name_role'];

    public $timestamps = false;
}

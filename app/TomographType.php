<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TomographType extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Hospital
 * Медицинское учреждение
 *
 * @package App
 */
class Hospital extends Model
{
    protected $fillable = ['name', 'description', 'address', 'technical_address', 'description_about', 'status'];
}

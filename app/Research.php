<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Research
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property boolean $state
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Research whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Research whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Research whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Research whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Research whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Research whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Research extends Model
{
    protected $fillable = ['name', 'description', 'state'];
}

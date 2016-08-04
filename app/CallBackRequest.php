<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель заявки на обратный звонок
 * 
 * Class CallBackRequest
 *
 * @package App
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\CallBackRequest whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CallBackRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CallBackRequest whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CallBackRequest extends Model
{
    //
}

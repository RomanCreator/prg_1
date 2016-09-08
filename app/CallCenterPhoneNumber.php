<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallCenterPhoneNumber extends Model
{
    protected $fillable = ['number'];
    protected $table = 'call_center_phone_numbers';


}

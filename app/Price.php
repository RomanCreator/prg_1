<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = ['hospital_id', 'research_id', 'price_from', 'price_to', 'description', 'status'];


    /**
     * Возвращает медицинское учреждение
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hospital() {
        return $this->belongsTo('App\Hospital', 'hospital_id');
    }


    /**
     * Возвращает исследование
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function research() {
        return $this->belongsTo('App\Research', 'research_id');
    }
}

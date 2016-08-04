<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Price
 *
 * @property integer $id
 * @property integer $hospital_id
 * @property integer $research_id
 * @property float $price_from
 * @property float $price_to
 * @property string $description
 * @property boolean $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Hospital $hospital
 * @property-read \App\Research $research
 * @method static \Illuminate\Database\Query\Builder|\App\Price whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Price whereHospitalId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Price whereResearchId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Price wherePriceFrom($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Price wherePriceTo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Price whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Price whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Price whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Price whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

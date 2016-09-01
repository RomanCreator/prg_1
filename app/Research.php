<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Image;
use Storage;

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

    /**
     * Возвращает лого медицинского учреждения
     *
     * @param int $width
     * @param int $height
     * @return null|string
     */
    public function getLogo($width = 150, $height = 200) {

        if (Storage::disk('public')->exists('researches/'.$this->id)) {
            if (!Storage::disk('public')->exists('researches/'.$this->id.'.derived_'.$width.'x'.$height.'.png')) {
                Image::make(Storage::disk('public')
                    ->get('researches/'.$this->id))
                    ->fit($width)
                    ->save(public_path().'/storage/researches/'.$this->id.'.derived_'.$width.'x'.$height.'.png');
            }

            $logo = Storage::disk('public')->url('researches/'.$this->id.'.derived_'.$width.'x'.$height.'.png');
            $logo .= '?'.time();
            return $logo;
        }

        return null;
    }

    public function delete() {
        /* удалим все миниатюры связанные с этим итемом */
        $IM = new ImageStorage($this);
        $IM->deleteNamespaceDir();
        return parent::delete(); // TODO: Change the autogenerated stub
    }
}

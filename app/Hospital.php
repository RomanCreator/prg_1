<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Image;
use Storage;

/**
 * Class Hospital
 * Медицинское учреждение
 *
 * @package App
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $address
 * @property integer $district Район города
 * @property string $weekwork
 * @property string $subway Ближайшее метро
 * @property string $technical_address
 * @property string $description_about
 * @property boolean $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereDistrict($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereWeekwork($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereSubway($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereTechnicalAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereDescriptionAbout($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Hospital extends Model
{
    protected $fillable = ['name', 'description', 'address', 'technical_address', 'description_about', 'status', 'general_hospital_id'];

    /**
     * Возвращает массив со строками содержащими время работы медицинского учреждения
     *
     * @return array | string
     */
    public function getWeekWorksTime() {
        $workTime = json_decode($this->weekwork, true);
        $daysOfWeek = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
        $returnedArr = [];
        for ($i = 0; $i < count($daysOfWeek); $i++) {
            if (isset($workTime[$i])) {
                $returnedArr[$i] = [
                    'day' => $daysOfWeek[$i],
                    'time' => $workTime[$i]['timeFrom'] . ' - ' . $workTime[$i]['timeTo']
                ];
            }
        }

        $previousTime = '';
        $nextIndex = '';
        $stringTime = [];

        $time = [];

        for ($i = 0; $i < count($daysOfWeek); $i++) {

            if (isset($returnedArr[$i])) {

                if (empty($stringTime)) {
                    $stringTime[0] = $returnedArr[$i]['day'];
                    $previousTime = $returnedArr[$i]['time'];
                    $nextIndex = $i;
                } else {
                    if ($previousTime === $returnedArr[$i]['time']) {
                        if ($nextIndex+1 == $i) {
                            $stringTime[1] = $returnedArr[$i]['day'];
                            $nextIndex = $i;
                        } else {
                            $time[] = $stringTime[0].'-'.$stringTime[1].': '.$previousTime;
                            $stringTime = [];
                            $stringTime[0] = $returnedArr[$i]['day'];
                            $previousTime = $returnedArr[$i]['time'];
                            $nextIndex = $i;
                        }
                    } else {
                        if (isset($stringTime[1])) {
                            $time[] = $stringTime[0].'-'.$stringTime[1].': '.$previousTime;
                        } else {
                            $time[] = $stringTime[0].': '.$previousTime;
                        }

                        $stringTime = [];
                        $stringTime[0] = $returnedArr[$i]['day'];
                        $previousTime = $returnedArr[$i]['time'];
                        $nextIndex = $i;
                    }
                }
            } else {
                if (!empty($stringTime)) {
                    if (isset($stringTime[1])) {
                        $time[] = $stringTime[0].'-'.$stringTime[1].': '.$previousTime;
                    } else {
                        $time[] = $stringTime[0].': '.$previousTime;
                    }

                    $stringTime = [];
                    $previousTime = '';
                    $nextIndex = [];
                }
            }

            if ($i == 6) {
                if (!empty($stringTime)) {
                    if (isset($stringTime[1])) {
                        $time[] = $stringTime[0].'-'.$stringTime[1].': '.$previousTime;
                    } else {
                        $time[] = $stringTime[0].': '.$previousTime;
                    }

                    $stringTime = [];
                    $previousTime = '';
                    $nextIndex = [];
                }
            }
        }

        return $time;
    }

    /**
     * Возвращает все исследования прикрепленные к данному учреждению в прайс листе
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function getResearches() {
        return $this->belongsToMany('App\Research', 'prices');
    }

    /**
     * Возвращает район в котором находится медицинское учреждение
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getDistrict() {
        return $this->belongsTo('App\District', 'district');
    }

    /**
     * Возвращает лого медицинского учреждения
     *
     * @param int $width
     * @param int $height
     * @return null|string
     */
    public function getLogo($width = 200, $height = 200) {

        if (Storage::disk('public')->exists('hospitals/'.$this->id)) {
            if (!Storage::disk('public')->exists('hospitals/'.$this->id.'.derived_'.$width.'x'.$height.'.png')) {
                Image::make(Storage::disk('public')
                    ->get('hospitals/'.$this->id))
                    ->fit($width, $height)
                    ->save(public_path().'/storage/hospitals/'.$this->id.'.derived_'.$width.'x'.$height.'.png');
            }

            $logo = Storage::disk('public')->url('hospitals/'.$this->id.'.derived_'.$width.'x'.$height.'.png');
            $logo .= '?'.time();
            return $logo;
        }

        return null;
    }


    /**
     * Возвращает все теги прикрепелнные к медицинскому учреждению
     * @return array
     */
    public function getTags() {
        $tags = explode(',', $this->tags);
        return $tags;
    }

    /**
     * Возвращает все типы поддерживаемых диагностики
     */
    public function TypeResearches () {
        return $this->belongsToMany('App\TypeResearch', 'hospital_type_research');
    }

    /**
     * Возвращает все типы томографов данного учреждения
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function TomographTypes () {
        return $this->belongsToMany('App\TomographType', 'hospital_tomograph_type');
    }

    /**
     * Возвращает false если нет головного офиса или является головным офисом, в
     * противном случае возвращает головное медицинское учреждение
     * @return bool|\Illuminate\Database\Eloquent\Collection|Model|null|static|static[]
     */
    public function GeneralHospital() {
        if ($this->is_general) {
            return false;
        }

        if (!is_null($this->general_hospital_id)) {
            $generalHospital = Hospital::find($this->general_hospital_id);
            if ($generalHospital) {
                return $generalHospital;
            }
        }

        return false;
    }

    /**
     * Возвращает массив с перечислением всех цен на типовые услуги
     * @return array
     */
    public function typeResearchesPrice () {
        $researches = explode(',', $this->type_researches_price);
        $returnedArr = [];
        foreach ($researches as $research) {
            if (!empty($research)) {
                $returnedArr[] = $research;
            }
        }
        return $returnedArr;
    }

    public function delete()
    {
        /* удалим все миниатюры связанные с этим итемом */
        $IM = new ImageStorage($this);
        $IM->deleteNamespaceDir();
        return parent::delete(); // TODO: Change the autogenerated stub
    }


}

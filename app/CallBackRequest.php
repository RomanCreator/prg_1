<?php

namespace App;

use App\Research;
use Auth;
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
    /**
     * Всевозможные статцусы заявки
     * @var array
     */
    public static $statusRequest = [
        'Новая',
        'В обработке',
        'Завершена'
    ];

    /**
     * Всевозможные состояния переходов
     * индексы соответсвуют индексам состояний в $statusRequests
     * @var array
     */
    public static $transitionAllowed = [
        [1, 2],
        [2],
        [1]
    ];

    /**
     * Проверяет возможность перехода статуса заявки и совершает его
     * если это возможно
     *
     * @param $idStatus
     * @return bool
     */
    public function changeStatusTo($idStatus) {
        if ($idStatus != 0 && $idStatus != $this->status) {
            $allowedStatuses = CallBackRequest::$transitionAllowed[$this->status];
            $nextTransitionKey = array_search($idStatus, $allowedStatuses);

            if ($nextTransitionKey!==false) {
                $this->status = $idStatus;
                return true;
            }
        } else {
            return true;
        }


        return false;
    }

    /**
     *  Возвращает массив доступных состояний перехода из текущего
     */
    public function getAllowedStatus () {
        $allowedStatus = [];
        if (!is_null($this->status)) {
            foreach (CallBackRequest::$transitionAllowed[$this->status] as $value) {
                $allowedStatus[] = [
                    'value' => $value,
                    'name' => CallBackRequest::$statusRequest[$value]
                ];
            }
        } else {
            $allowedStatus[] = [
                'value' => 0,
                'name' => CallBackRequest::$statusRequest[0]
            ];
        }

        return $allowedStatus;
    }

    /**
     * Возвращает название текущего статуса заявки
     * @return mixed
     */
    public function getNameOfCurrentStatus () {
        return CallBackRequest::$statusRequest[$this->status];
    }


    /**
     * Прикрепляет комментарий оператора к заявке
     * @param $comment
     */
    public function addComment ($comment) {
        $User = Auth::user();
        $comments = json_decode($this->comments, true);
        $comments[] = [
            'name' => $User->name,
            'comment' => $comment
        ];

        $this->comments = json_encode($comments);
    }

    /**
     * Возвращает массив комментариев к заявке
     */
    public function getComments () {
        return json_decode($this->comments, true);
    }

    /**
     * Осуществляет попытку совершить инициализацию исследования
     * если не может инициализировать исследование, то возвращает null
     * в противном случае возвращает исследование
     * @return Research | null
     */
    public function getResearch() {
        if (!is_null($this->research)) {
            $research = Research::find($this->research);
            if ($research) {
                return $research;
            }
        }

        return null;
    }


    /**
     * Производит попытку вернуть привязанный госпиталь, если это возможно
     * @return \Illuminate\Database\Eloquent\Collection|Model|null|static|static[]
     */
    public function getHospital() {
        if (!is_null($this->hospital_id)) {
            $hospital = Hospital::find($this->hospital_id);
            if ($hospital) {
                return $hospital;
            }
        }

        return null;
    }

}

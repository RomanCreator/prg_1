<?php

namespace App\Http\Controllers;

use App\Hospital;
use App\Research;
use Illuminate\Http\Request;

use App\Http\Requests;
use Image;
use Storage;

class FrontEndController extends Controller
{
    public function index() {
        $hospitals = Hospital::where('status', 1)
            ->take(5)
            ->get();

        foreach ($hospitals as &$hospital) {
            /* Получим рабочее время нашего медицинского центра */
            $timeWorks = $hospital->getWeekWorksTime();
            $hospital->timeWorks = $timeWorks;
            /* Получим правильный адрес нашего медицинского центра */
            /* Так как любой адрес в нашей системе содержит название страны и города */
            /* Через запятую, то мы можем спокойно их удалить из адреса */
            $address = $hospital->address;
            $address = explode(',', $address);
            if (count($address) > 2) {
                unset($address[0]);
                unset($address[1]);
                ksort($address);
                $address = implode(', ', $address);
                $hospital->address = $address;
            }




            if (Storage::disk('public')->exists('hospitals/'.$hospital->id)) {
                if (!Storage::disk('public')->exists('hospitals/'.$hospital->id.'.derived_150x200.png')) {
                    Image::make(Storage::disk('public')
                        ->get('hospitals/'.$hospital->id))
                        ->crop(150,200)
                        ->save(public_path().'/storage/hospitals/'.$hospital->id.'.derived_150x200.png');
                }

                $hospital->logo = Storage::disk('public')->url('hospitals/'.$hospital->id.'.derived_150x200.png');
                $hospital->logo .= '?'.time();
            }


        }

        $hospitalForMap = Hospital::where('status', 1)->get();
        $hospitalsData = [];
        foreach ($hospitalForMap as $hospital) {
            $timeWorks = $hospital->getWeekWorksTime();
            $address = $hospital->address;
            $address = explode(',', $address);
            if (count($address) > 2) {
                unset($address[0]);
                unset($address[1]);
                ksort($address);
                $address = implode(', ', $address);
            }


            $localData['technical_address'] = $hospital->technical_address;
            $localData['name'] = $hospital->name;
            $localData['district'] = !empty($hospital->getDistrict) ? $hospital->getDistrict->name : '';
            $localData['subway'] = $hospital->subway;
            $localData['address'] = $address;
            $localData['weekwork'] = $timeWorks;
            $hospitalsData[] = $localData;
        }

        $hospitalsData = json_encode($hospitalsData);

        $researches = Research::where('state', 1)->get();

        return view('welcome', [
            'researches' => $researches,
            'hospitals' => $hospitals,
            'hospitalsData' => $hospitalsData
        ]);
    }
}

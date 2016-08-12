<?php

namespace App\Http\Controllers;

use App\Hospital;
use App\ImageStorage;
use App\Price;
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

    public function hospitals () {
        $hospitals = Hospital::where('status', 1)->paginate(20);
        $research = Research::where('state', 1)->take(10)->get();

        return view('hospitals', [
            'researches' => $research,
            'hospitals'=>$hospitals,
            'title'=>'Медицинские центры'
        ]);
    }


    public function researches () {
        $researches = Research::where('state', 1)->paginate(20);
        return view('researches', [
            'researches' => $researches,
            'title' => 'Исследования'
        ]);
    }

    public function hospital($id) {
        $hospital = Hospital::find($id)->where('status', 1)->first();

        if (!$hospital) {
            abort(404,'Запрашеваемая страница не найдена или не существует');
        }

        $research = Research::where('state', 1)->take(10)->get();

        /* Вытаскиваем галерею */
        /* Район, адрес, время работы */
        /* Прайс лист */
        $IM = new ImageStorage($hospital);
        $gallerySmall = $IM->getCropped('gallery', 150, 90);
        $galleryBig = $IM->getCropped('gallery', 690, 490);

        $timeWorks = $hospital->getWeekWorksTime();

        $address = $hospital->address;
        $address = explode(',', $address);
        if (count($address) > 2) {
            unset($address[0]);
            unset($address[1]);
            ksort($address);
            $address = implode(', ', $address);
        }

        $district = $hospital->getDistrict->name;
        $subway = $hospital->subway;
        $name = $hospital->name;
        $description = $hospital->description;

        /* Прайс лист вытащим позже */
        $prices = Price::where([
            'hospital_id' => $hospital->id,
            'status' => 1
        ])->get();

        if ($prices->count() < 1) {
            $prices = false;
        }

        return view('hospital', [
            'researches' => $research,
            'gallerySmall' => $gallerySmall,
            'galleryBig' => $galleryBig,
            'address' => $address,
            'subway' => $subway,
            'timeToWork' => $timeWorks,
            'district' => $district,
            'description' => $description,
            'name' => $name,
            'prices' => $prices,
            'title' => $name
        ]);
    }
}

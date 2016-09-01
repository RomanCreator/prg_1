<?php

namespace App\Http\Controllers;

use App\CallBackRequest;
use App\District;
use App\Hospital;
use App\ImageStorage;
use App\Price;
use App\Research;
use App\TomographType;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use Image;
use Storage;

class FrontEndController extends Controller
{
    public function index() {

        $hospitals = Hospital::query()
            ->leftJoin('hospital_type_research', 'hospitals.id', '=', 'hospital_type_research.hospital_id')
            ->leftJoin('hospital_tomograph_type', 'hospitals.id', '=', 'hospital_tomograph_type.hospital_id')
            ->distinct();

        $districtSelected = false;
        $typeEquipmentSelected = false;
        $typeResearchSelected = false;

        if (!isset($_REQUEST['district']) && !isset($_REQUEST['type_equipment']) && !isset($_REQUEST['type_research'])) {
            $hospitals = $hospitals
                ->take(5);
        }


        if (isset($_REQUEST['type_research'])) {
            $typeResearchSelected = $_REQUEST['type_research'];
            $hospitals->where('type_research_id', $_REQUEST['type_research']);
        }

        if (isset($_REQUEST['type_equipment'])) {
            $typeEquipmentSelected = $_REQUEST['type_equipment'];
            $hospitals->where('tomograph_type_id', $_REQUEST['type_equipment']);
        }

        if (isset($_REQUEST['district'])) {
            $districtSelected = $_REQUEST['district'];
            $districts = District::where('name', 'like', '%'.$_REQUEST['district'].'%');
            $districtsId = [];
            foreach ($districts as $district) {
                $districtsId[] = $district->id;
            }

            $hospitals->whereIn('district', $districtsId)
                ->orWhere('subway', 'like', '%'.$_REQUEST['district'].'%');
        }




        $hospitals = $hospitals
                        ->where('status', 1)
                        ->paginate(10);


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
                        ->fit(150)
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

        $researches = Research::where('state', 1)->where('show_state', 1)->orderBy('show_position', 'asc')->get();
        $researches->sortBy('show_position');

        $tomographTypes = TomographType::all();

        return view('welcome', [
            'researches' => $researches,
            'hospitals' => $hospitals,
            'hospitalsData' => $hospitalsData,
            'tomographTypes' => $tomographTypes,
            'districtSelected' => $districtSelected,
            'typeEquipmentSelected' => $typeEquipmentSelected,
            'typeResearchSelected' => $typeResearchSelected
        ]);
    }

    public function hospitals () {
        $hospitals = Hospital::where('status', 1)->paginate(20);
        $researches = Research::where('state', 1)->where('show_state', 1)->orderBy('show_position', 'asc')->get();
        $researches->sortBy('show_position');

        return view('hospitals', [
            'researches' => $researches,
            'hospitals'=>$hospitals,
            'title'=>'Медицинские центры'
        ]);
    }


    public function search() {
        $searchStr = $_REQUEST['search'];

        $researches = Research::where('state', 1)->where('show_state', 1)->orderBy('show_position', 'asc')->get();
        $researches->sortBy('show_position');

        $hospitals = null;
        $researchesList = null;
        if (!empty ($searchStr)) {


            $hospitals = Hospital::where('status', 1)
                ->where(function ($query) use($searchStr) {
                    $query->where('name', 'like', '%'.$searchStr.'%')
                    ->orWhere('tags', 'like', '%'.$searchStr.'%');
                })->distinct()->get();

            $researchesList = Research::where('state', 1)
                ->where(function ($query) use($searchStr) {
                    $query->where('name', 'like', '%'.$searchStr.'%');
                })->distinct()->get();
        }

        return view('search', [
            'researches' => $researches,
            'search' => $searchStr,
            'hospitals' => $hospitals,
            'researchList' => $researchesList
        ]);
    }


    public function researches () {
        $researches = Research::where('state', 1)->paginate(20);
        $researchesTab = Research::where('state', 1)->where('show_state', 1)->orderBy('show_position', 'asc')->get();
        $researchesTab->sortBy('show_position');
        return view('researches', [
            'researches' => $researches,
            'researchesTab' => $researchesTab,
            'title' => 'Исследования'
        ]);
    }

    public function research ($id) {
        $research = Research::find($id);
        if (!$research || $research->state != 1) {
            abort(404,'Запрашеваемая страница не найдена или не существует');
        }

        $researchesTab = Research::where('state', 1)->where('show_state', 1)->orderBy('show_position', 'asc')->get();
        $researchesTab->sortBy('show_position');

        return view('research', [
            'researchesTab' => $researchesTab,
            'description' => $research->description,
            'name' => $research->name,
            'title' => $research->name
        ]);
    }

    public function hospital($id) {
        $hospital = Hospital::find($id);


        if (!$hospital || $hospital->status != 1) {
            abort(404,'Запрашеваемая страница не найдена или не существует');
        }

        $researches = Research::where('state', 1)->where('show_state', 1)->orderBy('show_position', 'asc')->get();
        $researches->sortBy('show_position');

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
            'id' => $hospital->id,
            'researches' => $researches,
            'gallerySmall' => $gallerySmall,
            'galleryBig' => $galleryBig,
            'address' => $address,
            'subway' => $subway,
            'timeToWork' => $timeWorks,
            'district' => $district,
            'description' => $description,
            'name' => $name,
            'prices' => $prices,
            'title' => $name,
            'tags' => $hospital->getTags()
        ]);
    }

    public function allresearches () {
        $researches = Research::where('state', 1)->get();
        $response = [];

        foreach ($researches as $research) {
            $row['val'] = $research->id;
            $row['name'] = $research->name;
            $response[] = $row;
        }

        return json_encode($response);
    }

    public function researchesfor ($id) {
        $hospital = Hospital::find($id);
        $response = [];
        if ($hospital) {
            $researches = $hospital->getResearches()->where('state', 1)->get();

            foreach ($researches as $research) {
                $row['val'] = $research->id;
                $row['name'] = $research->name;
                $response[] = $row;
            }
        }
        return json_encode($response);
    }

    public function callback_order(Request $request) {
        $order = new CallBackRequest();
        $order->name = $request->name;
        $order->phone = $request->phone;
        if (isset($request->research)) {
            $order->research = $request->research;
        }

        if (isset($request->message)) {
            $order->message = $request->message;
        }

        if (isset($request->hospital_id)) {
            $order->hospital_id = $request->hospital_id;
        }

        $order->status = 0;

        $order->save();

        return json_encode(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers\Backend\Location;

use \App\Http\Controllers\BackendController;
use App\Models\Entity\Business\Business_City;
use App\Models\Entity\Business\Business_Country;
use App\Models\Services\Globals;
use Illuminate\Http\Request;

class CityController extends BackendController
{
    public function index(Request $request, Business_Country $countryInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        //get model
        $businessCity = Globals::getBusiness('City');

        //get list city
        $arrListCity = $businessCity->getListCity($countryInfo->country_id);

        return view('backend.location.city.index', compact('arrListCity', 'countryInfo'));
    }

    public function create(Request $request, Business_Country $countryInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        //get model
        $businessCountry = Globals::getBusiness('Country');

        //get list country
        $arrListCountry = $businessCountry->getListCountry();

        return view('backend.location.city.create', compact('arrListCountry', 'countryInfo'));
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        $this->validate($request, [
            'city_name' => 'required|max:100',
            'country_id' => 'required|exists:country,country_id,status,' . config('cms.backend.status.active'),
            'city_zipcode' => 'numeric',
            'city_location' => 'max:50'
        ], [
            'city_name.required' => trans('validation.location.city.city_name.required'),
            'city_name.max' => trans('validation.location.city.city_name.maxlength'),
            'country_id.required' => trans('validation.location.city.country_id.required'),
            'country_id.exists' => trans('validation.location.city.country_id.not_exist'),
            'city_zipcode.numeric' => trans('validation.location.city.city_zipcode.number'),
            'city_location.max' => trans('validation.location.city.city_location.maxlength'),
        ]);

        //get model
        $businessCity = Globals::getBusiness('City');

        $params = [
            'city_name' => $request->city_name,
            'city_zipcode' => $request->city_zipcode,
            'city_location' => $request->city_location,
            'city_order' => $businessCity->getLastOrder($request->country_id),
            'country_id' => $request->country_id
        ];

        $businessCity->create($params);

        return response()->json(['error' => 0, 'message' => trans('common.messages.location.city.created')]);
    }

    public function edit(Request $request, Business_City $cityInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        //get model
        $businessCountry = Globals::getBusiness('Country');

        //get list country
        $arrListCountry = $businessCountry->getListCountry();

        return view('backend.location.city.edit', compact('arrListCountry', 'cityInfo'));
    }

    public function update(Request $request, Business_City $cityInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        $this->validate($request, [
            'city_name' => 'required|max:100',
            'country_id' => 'required|exists:country,country_id,status,' . config('cms.backend.status.active'),
            'city_zipcode' => 'numeric',
            'city_location' => 'max:50'
        ], [
            'city_name.required' => trans('validation.location.city.city_name.required'),
            'city_name.max' => trans('validation.location.city.city_name.maxlength'),
            'country_id.required' => trans('validation.location.city.country_id.required'),
            'country_id.exists' => trans('validation.location.city.country_id.not_exist'),
            'city_zipcode.numeric' => trans('validation.location.city.city_zipcode.number'),
            'city_location.max' => trans('validation.location.city.city_location.maxlength'),
        ]);

        $params = [
            'city_name' => $request->city_name,
            'city_zipcode' => $request->city_zipcode,
            'city_location' => $request->city_location,
            'country_id' => $request->country_id
        ];

        $cityInfo->update($params);

        return response()->json(['error' => 0, 'message' => trans('common.messages.location.city.updated')]);
    }

    public function destroy(Request $request, Business_City $cityInfo)
    {
        $cityInfo->delete();

        return response()->json(['error' => 0, 'message' => trans('common.messages.location.city.deleted')]);
    }

    public function updateSort(Request $request)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        //get model
        $businessCity = Globals::getBusiness('City');

        $arrId = $request->id ?? [];
        if (!empty($arrId)) {
            foreach ($arrId as $index => $id) {
                $businessCity->find($id)->update([
                    'city_order' => $index + 1
                ]);
            }
        }

        return response()->json(['error' => 0, 'message' => trans('common.messages.sort_success')]);
    }
}

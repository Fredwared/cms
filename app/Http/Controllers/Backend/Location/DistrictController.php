<?php

namespace App\Http\Controllers\Backend\Location;

use \App\Http\Controllers\BackendController;
use App\Models\Entity\Business\Business_City;
use App\Models\Entity\Business\Business_District;
use App\Models\Services\Globals;
use Illuminate\Http\Request;

class DistrictController extends BackendController
{
    public function index(Request $request, Business_City $cityInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        //get model
        $businessDistrict = Globals::getBusiness('District');

        //get list city
        $arrListDistrict = $businessDistrict->getListDistrict($cityInfo->city_id);

        return view('backend.location.district.index', compact('arrListDistrict', 'cityInfo'));
    }

    public function create(Request $request, Business_City $cityInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        //get model
        $businessCity = Globals::getBusiness('City');

        //get list country
        $arrListCity = $businessCity->getListCity($cityInfo->country->country_id);

        return view('backend.location.district.create', compact('arrListCity', 'cityInfo'));
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        $this->validate($request, [
            'district_name' => 'required|max:100',
            'city_id' => 'required|exists:city,city_id,status,' . config('cms.backend.status.active'),
            'district_location' => 'max:50'
        ], [
            'district_name.required' => trans('validation.location.district.district_name.required'),
            'district_name.max' => trans('validation.location.district.district_name.maxlength'),
            'city_id.required' => trans('validation.location.district.city_id.required'),
            'city_id.exists' => trans('validation.location.district.city_id.not_exist'),
            'district_location.max' => trans('validation.location.district.district_location.maxlength'),
        ]);

        //get model
        $businessDistrict = Globals::getBusiness('District');

        $params = [
            'district_name' => $request->district_name,
            'district_location' => $request->district_location,
            'district_order' => $businessDistrict->getLastOrder($request->city_id),
            'city_id' => $request->city_id
        ];

        $businessDistrict->create($params);

        return response()->json(['error' => 0, 'message' => trans('common.messages.location.district.created')]);
    }

    public function edit(Request $request, Business_District $districtInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        //get model
        $businessCity = Globals::getBusiness('City');

        //get list country
        $arrListCity = $businessCity->getListCity($districtInfo->city->country->country_id);

        return view('backend.location.district.edit', compact('arrListCity', 'districtInfo'));
    }

    public function update(Request $request, Business_District $districtInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        $this->validate($request, [
            'district_name' => 'required|max:100',
            'city_id' => 'required|exists:city,city_id,status,' . config('cms.backend.status.active'),
            'district_location' => 'max:50'
        ], [
            'district_name.required' => trans('validation.location.district.district_name.required'),
            'district_name.max' => trans('validation.location.district.district_name.maxlength'),
            'city_id.required' => trans('validation.location.district.city_id.required'),
            'city_id.exists' => trans('validation.location.district.city_id.not_exist'),
            'district_location.max' => trans('validation.location.district.district_location.maxlength'),
        ]);

        $params = [
            'district_name' => $request->district_name,
            'district_location' => $request->district_location,
            'city_id' => $request->city_id
        ];

        $districtInfo->update($params);

        return response()->json(['error' => 0, 'message' => trans('common.messages.location.district.updated')]);
    }

    public function destroy(Request $request, Business_District $districtInfo)
    {
        $districtInfo->delete();

        return response()->json(['error' => 0, 'message' => trans('common.messages.location.district.deleted')]);
    }

    public function updateSort(Request $request)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        //get model
        $businessDistrict = Globals::getBusiness('District');

        $arrId = $request->id ?? [];
        if (!empty($arrId)) {
            foreach ($arrId as $index => $id) {
                $businessDistrict->find($id)->update([
                    'district_order' => $index + 1
                ]);
            }
        }

        return response()->json(['error' => 0, 'message' => trans('common.messages.sort_success')]);
    }
}

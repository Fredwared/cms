<?php

namespace App\Http\Controllers\Backend\Location;

use \App\Http\Controllers\BackendController;
use App\Models\Entity\Business\Business_District;
use App\Models\Entity\Business\Business_Ward;
use App\Models\Services\Globals;
use Illuminate\Http\Request;

class WardController extends BackendController
{
    public function index(Request $request, Business_District $districtInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        //get model
        $businessWard = Globals::getBusiness('Ward');

        //get list city
        $arrListWard = $businessWard->getListWard($districtInfo->district_id);

        return view('backend.location.ward.index', compact('arrListWard', 'districtInfo'));
    }

    public function create(Request $request, Business_District $districtInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        //get model
        $businessDistrict = Globals::getBusiness('District');

        //get list country
        $arrListDistrict = $businessDistrict->getListDistrict($districtInfo->city->city_id);

        return view('backend.location.ward.create', compact('arrListDistrict', 'districtInfo'));
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        $this->validate($request, [
            'ward_name' => 'required|max:100',
            'district_id' => 'required|exists:district,district_id,status,' . config('cms.backend.status.active'),
            'ward_location' => 'max:50'
        ], [
            'ward_name.required' => trans('validation.location.ward.ward_name.required'),
            'ward_name.max' => trans('validation.location.ward.ward_name.maxlength'),
            'district_id.required' => trans('validation.location.ward.district_id.required'),
            'district_id.exists' => trans('validation.location.ward.district_id.not_exist'),
            'ward_location.max' => trans('validation.location.ward.ward_location.maxlength'),
        ]);

        //get model
        $businessWard = Globals::getBusiness('Ward');

        $params = [
            'ward_name' => $request->ward_name,
            'ward_location' => $request->ward_location,
            'ward_order' => $businessWard->getLastOrder($request->district_id),
            'district_id' => $request->district_id
        ];

        $businessWard->create($params);

        return response()->json(['error' => 0, 'message' => trans('common.messages.location.ward.created')]);
    }

    public function edit(Request $request, Business_Ward $wardInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        //get model
        $businessDistrict = Globals::getBusiness('District');

        //get list country
        $arrListDistrict = $businessDistrict->getListDistrict($wardInfo->district->city->city_id);

        return view('backend.location.ward.edit', compact('arrListDistrict', 'wardInfo'));
    }

    public function update(Request $request, Business_Ward $wardInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        $this->validate($request, [
            'ward_name' => 'required|max:100',
            'district_id' => 'required|exists:district,district_id,status,' . config('cms.backend.status.active'),
            'ward_location' => 'max:50'
        ], [
            'ward_name.required' => trans('validation.location.ward.ward_name.required'),
            'ward_name.max' => trans('validation.location.ward.ward_name.maxlength'),
            'district_id.required' => trans('validation.location.ward.district_id.required'),
            'district_id.exists' => trans('validation.location.ward.district_id.not_exist'),
            'ward_location.max' => trans('validation.location.ward.ward_location.maxlength'),
        ]);

        $params = [
            'ward_name' => $request->ward_name,
            'ward_location' => $request->ward_location,
            'district_id' => $request->district_id
        ];

        $wardInfo->update($params);

        return response()->json(['error' => 0, 'message' => trans('common.messages.location.ward.updated')]);
    }

    public function destroy(Request $request, Business_Ward $wardInfo)
    {
        $wardInfo->delete();

        return response()->json(['error' => 0, 'message' => trans('common.messages.location.ward.deleted')]);
    }

    public function updateSort(Request $request)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        //get model
        $businessWard = Globals::getBusiness('Ward');

        $arrId = $request->id ?? [];
        if (!empty($arrId)) {
            foreach ($arrId as $index => $id) {
                $businessWard->find($id)->update([
                    'ward_order' => $index + 1
                ]);
            }
        }

        return response()->json(['error' => 0, 'message' => trans('common.messages.sort_success')]);
    }
}

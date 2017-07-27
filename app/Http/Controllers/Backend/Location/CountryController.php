<?php

namespace App\Http\Controllers\Backend\Location;

use \App\Http\Controllers\BackendController;
use App\Models\Entity\Business\Business_Country;
use App\Models\Services\Globals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CountryController extends BackendController
{
    public function index(Request $request)
    {
        //get model
        $businessCountry = Globals::getBusiness('Country');

        //get list country
        $arrListCountry = $businessCountry->getListCountry();

        return view('backend.location.country.index', compact('arrListCountry'));
    }

    public function create(Request $request)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        return view('backend.location.country.create');
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        $this->validate($request, [
            'country_name' => 'required|max:100',
            'country_code' => 'required|max:15'
        ], [
            'country_name.required' => trans('validation.location.country.country_name.required'),
            'country_name.max' => trans('validation.location.country.country_name.maxlength'),
            'country_code.required' => trans('validation.location.country.country_code.required'),
            'country_code.max' => trans('validation.location.country.country_code.maxlength')
        ]);

        //get model
        $businessCountry = Globals::getBusiness('Country');

        $params = [
            'country_name' => $request->country_name,
            'country_code' => $request->country_code,
            'country_order' => $businessCountry->getLastOrder()
        ];

        $businessCountry->create($params);

        return response()->json(['error' => 0, 'message' => trans('common.messages.location.country.created')]);
    }

    public function edit(Request $request, Business_Country $countryInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        return view('backend.location.country.edit', compact('countryInfo'));
    }

    public function update(Request $request, Business_Country $countryInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        $this->validate($request, [
            'country_name' => 'required|max:100',
            'country_code' => 'required|max:15'
        ], [
            'country_name.required' => trans('validation.location.country.country_name.required'),
            'country_name.max' => trans('validation.location.country.country_name.maxlength'),
            'country_code.required' => trans('validation.location.country.country_code.required'),
            'country_code.max' => trans('validation.location.country.country_code.maxlength')
        ]);

        $params = [
            'country_name' => $request->country_name,
            'country_code' => $request->country_code
        ];

        $countryInfo->update($params);

        return response()->json(['error' => 0, 'message' => trans('common.messages.location.country.updated')]);
    }

    public function destroy(Request $request, Business_Country $countryInfo)
    {
        $countryInfo->delete();

        return response()->json(['error' => 0, 'message' => trans('common.messages.location.country.deleted')]);
    }

    public function updateSort(Request $request)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.location.index'));
        }

        //get model
        $businessCountry = Globals::getBusiness('Country');

        $arrId = $request->id ?? [];
        if (!empty($arrId)) {
            foreach ($arrId as $index => $id) {
                $businessCountry->find($id)->update([
                    'country_order' => $index + 1
                ]);
            }
        }

        return response()->json(['error' => 0, 'message' => trans('common.messages.sort_success')]);
    }
}

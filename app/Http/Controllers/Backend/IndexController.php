<?php

namespace App\Http\Controllers\Backend;

use \App\Http\Controllers\BackendController;
use Illuminate\Http\Request;

class IndexController extends BackendController
{
    public function index(Request $request)
    {
        return view('backend.index.index');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    use ValidatesRequests;

    public function __construct(Request $request)
    {

    }
}

<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;

class IndexController extends FrontendController
{
    public function index(Request $request)
    {
        return view('frontend.index.welcome');
    }
}

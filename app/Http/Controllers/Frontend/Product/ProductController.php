<?php
namespace App\Http\Controllers\Frontend\Product;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;

class ProductController extends FrontendController
{
    public function index(Request $request, $title, $id)
    {
        dd($title, $id);
        return view('frontend.product.detail');
    }
}

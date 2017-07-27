<?php
namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\BackendController;
use Illuminate\Http\Request;

class ProductController extends BackendController
{
    public function index(Request $request)
    {
        return view('backend.product.index');
    }
}

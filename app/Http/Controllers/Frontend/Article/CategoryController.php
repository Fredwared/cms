<?php
namespace App\Http\Controllers\Frontend\Article;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;

class CategoryController extends FrontendController
{
    public function index(Request $request, $cate_code)
    {
        dd($cate_code);
        return view('frontend.article.category');
    }
}

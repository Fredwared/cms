<?php
namespace App\Http\Controllers\Frontend\Article;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;

class ArticleController extends FrontendController
{
    public function index(Request $request, $title, $id)
    {
        dd($title, $id);
        return view('frontend.article.detail');
    }
}

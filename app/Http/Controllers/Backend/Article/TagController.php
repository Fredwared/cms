<?php
namespace App\Http\Controllers\Backend\Article;

use App\Http\Controllers\BackendController;
use Illuminate\Http\Request;

class TagController extends BackendController
{
    public function index(Request $request)
    {
        return redirect(route('backend.index'));
        //return view('backend.article.tag.index');
    }
}

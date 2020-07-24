<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Request $request,Category $category)
    {
        $topics=$category->topic()->withOrder($request->order)->with('user','category')->paginate(10);
        return view('topics.index',compact('topics','category'));
    }
}

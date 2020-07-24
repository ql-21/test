<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('verified');
    }

    public function root()
    {
        return view('pages.root');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications=Auth::user()->notifications()->paginate(5);
        Auth::user()->markAsRead();
        return view('notifications.index',compact('notifications'));
    }
}

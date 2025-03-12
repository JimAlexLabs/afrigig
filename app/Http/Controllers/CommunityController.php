<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index()
    {
        return view('community.index');
    }

    public function discussions()
    {
        return view('community.discussions');
    }

    public function events()
    {
        return view('community.events');
    }
} 
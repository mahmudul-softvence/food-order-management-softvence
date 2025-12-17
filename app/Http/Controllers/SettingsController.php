<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function general()
    {
        return view('backend.settings.general');
    }

    public function logo()
    {
        return view('backend.settings.logo');
    }

    public function contact()
    {
        return view('backend.settings.contact');
    }
}

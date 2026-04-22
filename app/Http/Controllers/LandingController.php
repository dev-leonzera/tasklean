<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Exibe a landing page do TaskLean
     */
    public function index()
    {
        return view('landing');
    }
}

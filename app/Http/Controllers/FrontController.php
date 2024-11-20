<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FrontController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('front.welcome'); 
    }

    public function login(Request $request)
    {
        return $request->all(); 
    }
}

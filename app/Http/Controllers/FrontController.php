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
    
    public function services()
    {
        return view('front.services'); 
    }

    public function terms()
    {
        return view('front.terms'); 
    }

    public function privacy()
    {
        return view('front.privacy'); 
    }

    public function about()
    {
        return view('front.about');  
    }
}

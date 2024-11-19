<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class FrontController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('front.welcome'); 
    }

    /**
     * Show login form
     */
    public function login_form()
    {
        return view('auth.login'); 
    }

    /**
     * Show register form
     */
    public function register_form()
    {
        return view('auth.register'); 
    }
}

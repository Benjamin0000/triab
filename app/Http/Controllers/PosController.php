<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pos.sales');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function login_page()
    {
        return view('pos.login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function login(Request $request)
    {
        //
    }

}
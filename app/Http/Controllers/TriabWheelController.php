<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth; 
use App\Models\WheelGlobal; 

class TriabWheelController extends Controller implements HasMiddleware
{
     /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
           'auth'
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wheel = WheelGlobal::where('user_id', Auth::id())->first();
        $available_funds = $wheel ? $wheel->main_balance : 0;
        $total_received = $wheel ? $wheel->total_received: 0;
        $stage = $wheel ? $wheel->stage : 0;
        $level = $wheel ? $wheel->level : 0;
        $total_referrals = $wheel ? $wheel->total_refs : 0;

        return view('app.community.index', compact(
            'wheel', 
            'available_funds', 
            'total_received', 
            'stage',
            'level', 
            'total_referrals'
        )); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

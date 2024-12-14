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
        $Level = $wheel ? $wheel->level : 0;
        $total_referrals = $wheel ? $wheel->total_refs : 0;
        $times = $wheel ? $wheel->times_received : 0; 

        return view('app.community.index', compact(
            'wheel', 
            'available_funds',  
            'total_received', 
            'stage',
            'Level', 
            'total_referrals', 
            'times'
        )); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function move_to_main()
    {
        $wheel = WheelGlobal::where('user_id', Auth::id())->first();
        if(!$wheel) return back(); 
        $amt = $wheel->main_balance; 

        if($amt > 0){
            $wheel->user->credit_main_balance(
                $amt, 
                'Triab Community', 
                'From Triab community available balance'
            ); 
            $wheel->main_balance = 0; 
            $wheel->save(); 
            return back()->with('success', 'Funds moved to main balance');
        }
        return back(); 
    }
}

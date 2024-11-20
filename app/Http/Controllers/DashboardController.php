<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth; 

class DashboardController extends Controller implements HasMiddleware
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
        return view('app.dashboard.index'); 
    }

    public function logout()
    {
        Auth::logout(); 
        return redirect(route('login'))->with('success', 'Logout Successful'); 
    }
}

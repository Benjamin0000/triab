<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth; 
use App\Models\Package; 

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
        $packages = Package::orderBy('cost', 'asc')->get(); 
        return view('app.dashboard.index', compact('packages')); 
    }

    public function logout()
    {
        Auth::logout(); 
        return redirect(route('login'))->with('success', 'Logout Successful'); 
    }
}

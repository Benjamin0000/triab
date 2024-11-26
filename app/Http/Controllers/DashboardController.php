<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth; 
use App\Models\Package;
use App\Models\TrxHistory; 


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
    {; 
        $packages = Package::orderBy('cost', 'asc')->get(); 
        $transactions = TrxHistory::where('user_id', Auth::id())->latest()->paginate(10);
        return view('app.dashboard.index', compact('packages', 'transactions')); 
    }

    public function load_more_transactions()
    {
        $transactions = TrxHistory::where('user_id', Auth::id())->latest()->paginate(10);
        $view = view('app.dashboard.trx_history_table', compact('transactions')); 
        return [
            "view"=>"$view"
        ]; 
    }

    public function select_package(Request $request)
    {
        $id = $request->input('id');
        $package = Package::find($id);
        if(!$package)
            return ['error'=>"Invalid package"];

        $cost = $package->cost;
        $discount = $package->discount;
        $cashback = $package->cashback;

        $user = Auth::user(); 
        $current_package = $user->package;

        if($current_package){  //user already have a package  
            if($current_package->cost >= $cost)
                return ['error'=>"Invalid package"]; 
            $cost -= $current_package->cost; 
        }else{
            if($discount)
                $cost -= calculate_pct($cost, $discount);    
        }

        if($cost > $user->main_balance)
            return ['error'=>"Insufficient funds"];

        if($cost > 0){
            $source = "Package Purchase";
            $desc = $package->name.' Purchase'; 
            $user->debit_main_balance($cost, $source, $desc);

            $cashback = $package->cashback;
            if($cashback > 0) {
                $desc .= " Cashback"; 
                $user->credit_main_balance($cashback, $source, $desc); 
            }
        }

        $user->package_id = $package->id;
        $user->save();
        credit_package_and_PV_ref_commission($user, $cost); 
        return ['success'=>"Package selected"];
    }

    public function logout()
    {
        Auth::logout(); 
        return redirect(route('login'))->with('success', 'Logout Successful'); 
    }
}

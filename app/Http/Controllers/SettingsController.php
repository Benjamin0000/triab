<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth; 
use App\Models\Package; 

class SettingsController extends Controller implements HasMiddleware
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
        return view('app.admin.settings.index'); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function packages()
    {
        $packages = Package::orderBy('cost', 'asc')->paginate(); 
        return view('app.admin.settings.package.index', compact('packages')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create_package(Request $request)
    {
        $request->validate([
            'name'=>['required', 'max:100'], 
            'cost'=>['required'],
            'discount'=>['required'], 
            'level'=>['required'], 
            'cashback'=>['required']
        ]); 

        $name = $request->input('name');
        $cost = (float) $request->input('cost');
        $cashback = (float) $request->input('cashback');
        $discount = (float) $request->input('discount');
        $level = (int) $request->input('level');
        $services = (array) $request->input('services');

        
        if (empty($services)) {
            return ['error' => "Please select a service"]; 
        }
        
        Package::create([
            'name'      => $name,
            'cost'      => $cost,
            'discount'  => $discount,
            'max_gen'   => $level,
            'services'  => $services,
            'cashback'  => $cashback
        ]);
        
        return ['success' => 'Package created'];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_package(Request $request, string $id)
    {
        $package = Package::find($id); 
        if(!$package) return ['error'=>"Package not found"]; 

        $request->validate([
            'name'=>['required', 'max:100'],
            'cost'=>['required'],
            'discount'=>['required'],
            'level'=>['required'],
            'cashback'=>['required']
        ]); 

        $data = $request->only(['name', 'cost', 'discount', 'level', 'services', 'cashback']); 
        $data['max_gen'] = (int) $data['level'];

        if( !isset($data['services']) || empty($data['services']) )
            $data['services'] = ""; 
        
        $package->update($data);
        return ['success' => "Package updated"];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_package(string $id)
    {
        $package = Package::find($id); 
        if(!$package) return back()->with('error', "Package not found"); 

        if($package->total_users <= 0){
            $package->delete(); 
            return back()->with('success', 'Package Deleted'); 
        }
        return back(); 
    }

    public function update_package_parameters(Request $request)
    {
        $cashback = $request->input('cashback'); 
        $pv = $request->input('pv'); 
        $pv_price = $request->input('pv_price'); 
        $pv_to_token = $request->input('pv_to_token'); 
        $triab_local_cost = $request->input('triab_local_cost'); 
        $min_triab_local_package_cost = $request->input('min_triab_local_package_cost'); 
        $triab_global_cost = $request->input('triab_global_cost'); 
        $min_triab_global_package_cost = $request->input('min_triab_global_package_cost'); 

        set_register('cashback', $cashback); 
        set_register('pv', $pv); 
        set_register('pv_price', $pv_price); 
        set_register('pv_to_token', $pv_to_token); 

        set_register('triab_local_cost', $triab_local_cost); 
        set_register('min_triab_local_package_cost', $min_triab_local_package_cost); 
        set_register('triab_global_cost', $triab_global_cost); 
        set_register('min_triab_global_package_cost', $min_triab_global_package_cost); 

        return back()->with('success', 'Updated'); 
    }

    public function reward_settings()
    {
        return view('app.admin.settings.reward.index'); 
    }

    public function update_reward_data(Request $request)
    {
        $pv_to_cash = $request->input('pv_to_cash');
        $pv_cash = $request->input('pv_cash');
        $pv_to_health = $request->input('pv_to_health');
        $pv_to_token = $request->input('pv_to_token');
        $token_to_coin = $request->input('token_to_coin');
        $coin_reward = $request->input('coin_reward');

        set_register('pv_to_cash', $pv_to_cash);
        set_register('pv_cash', $pv_cash);
        set_register('pv_to_health', $pv_to_health);
        set_register('pv_to_token', $pv_to_cash);
        set_register('token_to_coin', $token_to_coin);
        set_register('coin_reward', $coin_reward);

        return back()->with('success', 'Updated');
    }
}

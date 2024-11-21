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
        return view('app.admin.settings.packages', compact('packages')); 
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
            'level'=>['required']
        ]); 

        $name = $request->input('name'); 
        $cost = (float)$request->input('cost'); 
        $discount = (float)$request->input('discount'); 
        $level = (int)$request->input('level'); 

        Package::create([
            'name'=>$name,
            'cost'=>$cost,
            'discount'=>$discount,
            'max_gen'=>$level
        ]); 

        return ['success'=>'Package created']; 
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
            'level'=>['required']
        ]); 

        $data = $request->all(); 
        $data['max_gen'] = $data['level']; 
        $package->update($data); 
        return ['success'=>"Package updated"]; 
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

    public function update_parameters(Request $request)
    {
        $cashback = $request->input('cashback'); 
        $pv = $request->input('pv'); 
        $pv_price = $request->input('pv_price'); 
        $pv_to_token = $request->input('pv_to_token'); 

        set_register('cashback', $cashback); 
        set_register('pv', $pv); 
        set_register('pv_price', $pv_price); 
        set_register('pv_to_token', $pv_to_token); 
        
        return back()->with('success', 'Updated'); 
    }
}

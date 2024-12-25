<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use App\Models\Package;
use App\Models\ShopCategory;  

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


        $all_services = all_services();
        $services = []; 

        foreach($all_services as $service){
            $count = $request->input($service, 0);
            $services[$service] = (int) $count;
        }

        if(empty($services))
            return ['error'=>'Please enter a service quantity'];

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

        $data = $request->only(['name', 'cost', 'discount', 'level', 'cashback']); 
        $data['max_gen'] = (int) $data['level'];

        $all_services = all_services();
        $services = []; 

        foreach($all_services as $service){
            $count = $request->input($service, 0);
            $services[$service] = (int) $count;
        }

        if(empty($services))
            return ['error'=>'Please enter a service quantity'];

        $data['services'] = $services; 
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

        set_register('cashback', $cashback); 
        set_register('pv', $pv); 
        set_register('pv_price', $pv_price); 
        set_register('pv_to_token', $pv_to_token); 

        return back()->with('success', 'Updated'); 
    }

    public function reward_settings()
    {
        return view('app.admin.settings.reward.index'); 
    }

    public function eshop_settings()
    {
        $categories = ShopCategory::all(); 
        return view('app.admin.settings.eshop.index', compact('categories')); 
    }

    public function create_eshop_category(Request $request)
    {
        $request->validate([
            'name'=>['required', 'max:100'], 
            'logo'=>['required', 'mimes:jpeg,png,jpg,gif,webp']
        ]); 
        
        $logo = $request->file('logo');
        $upload = upload_poster($logo, 100);

        if(isset($upload['error'])) 
            return back()->with('error', $upload['error']);

        $logo = $upload['path'];
        $data = $request->all(); 

        $data['icon'] = $logo; 
        ShopCategory::create($data); 
        return back()->with('success', 'Category created'); 
    }

    public function update_eshop_category(Request $request, $id)
    {
        $request->validate([
            'name'=>['required', 'max:100'], 
            'logo'=>['nullable', 'mimes:jpeg,png,jpg,gif,webp']
        ]); 
        $category = ShopCategory::findOrFail($id);

        $data = $request->all();
        $logo = $request->file('logo');

        if($logo){
            $upload = upload_poster($logo, 100);

            if(isset($upload['error'])) 
                return back()->with('error', $upload['error']);

            $logo = $upload['path'];
            $data['icon'] = $logo; 

            if(Storage::disk('public')->exists($category->icon))
                Storage::disk('public')->delete($category->icon);
        }

        $category->update($data);
        return back()->with('success', 'Category updated'); 
    }

    public function delete_eshop_category($id)
    {
        $category = ShopCategory::findOrFail($id);

        if($category->total_shops() > 0)
            return back()->with('error', 'This category already has shops');
        
        if(Storage::disk('public')->exists($category->icon))
            Storage::disk('public')->delete($category->icon);

        $category->delete(); 

        return back()->with('success', 'category deleted'); 
    }


    public function update_reward_data(Request $request)
    {
        $pv_to_cash = $request->input('pv_to_cash');
        $pv_cash_reward = $request->input('pv_cash_reward');



        // $pv_cash = $request->input('pv_cash');
        // $pv_to_health = $request->input('pv_to_health');


        // $pv_to_token = $request->input('pv_to_token');


        // $token_to_coin = $request->input('token_to_coin');
        // $coin_reward = $request->input('coin_reward');

        set_register('pv_to_cash', $pv_to_cash);
        set_register('pv_cash_reward', $pv_cash_reward);


        // set_register('pv_to_health', $pv_to_health);
        // set_register('pv_to_token', $pv_to_cash);
        // set_register('token_to_coin', $token_to_coin);
        // set_register('coin_reward', $coin_reward);

        return back()->with('success', 'Updated');
    }
}

<?php

namespace App\Http\Controllers\Eshop;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Staff; 

class PosController extends Controller implements HasMiddleware
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
    public function index(string $shop_id)
    {
        $shop = Shop::where([
            ['id', $shop_id],
            ['user_id', Auth::id()]
        ])->first();

        if(!$shop)
            return back()->with('error', 'Invalid shop');

        $staffs = Staff::where('shop_id', $shop->id)->latest()->paginate(20);
        return view('app.eshop.pos.index', compact('shop', 'staffs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_staff(Request $request)
    {
        $request->validate([
            'id'=>['required', 'max:100'],
            'name'=>['required', 'max:100'],
            'pass_code'=>['required', 'max:100'],
            'role'=>['required']
        ]); 

        $shop_id = $request->input('id');
        $pass_code = $request->input('pass_code');
        $name = $request->input('name');
        $role = (int)$request->input('role');

        $shop = Shop::where([
            ['id', $shop_id],
            ['user_id', Auth::id()]
        ])->exists();

        if(!$shop)
            return ['error'=>"Invalid"];

        $exists = Staff::where([
            ['shop_id', $shop_id],
            ['pass_code', $pass_code]
        ])->exists();

        if($exists)
            return ['error'=>"Pass code already exists"];

        Staff::create([
            'shop_id'=>$shop_id, 
            'name'=>$name,
            'admin'=>$role, 
            'pass_code'=>$pass_code
        ]); 

        return ['success'=>"Staff created"]; 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update_staff(Request $request)
    {
        $request->validate([
            'id'=>['required', 'max:100'],
            'name'=>['required', 'max:100'],
            'pass_code'=>['required', 'max:100'],
            'role'=>['required'], 
            'shop_id'=>['required']
        ]); 
        $id = $request->input('id');
        $shop_id = $request->input('shop_id');
        $name = $request->input('name');
        $pass_code = $request->input('pass_code'); 
        $role = (int)$request->input('role');
        $status = (int)$request->input('status'); 


        $staff = Staff::where([
            ['id', $id], 
            ['shop_id', $shop_id]
        ])->first(); 

        if(!$staff)
            return ['error'=>"Invalid staff"]; 

        if($staff->shop->user_id != Auth::id())
            return ['error'=>"Invalid Operation"];

        $exists = Staff::where([
            ['id', '<>', $id], 
            ['shop_id', $shop_id],
            ['pass_code', $pass_code]
        ])->exists();

        if($exists)
            return ['error'=>"Pass code already exists"];

        $staff->update([
            'shop_id'=>$shop_id, 
            'name'=>$name,
            'admin'=>$role, 
            'pass_code'=>$pass_code, 
            'status'=>$status
        ]);

        return ['success'=>"Staff updated"]; 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete_staff(string $id)
    {
        $staff = Staff::findOrFail($id);

        if($staff->shop->user_id != Auth::id())
            return back()->with('error', 'Invalid Operation');

        $staff->delete(); 
        return back()->with('success', 'Staff deleted'); 
    }
}

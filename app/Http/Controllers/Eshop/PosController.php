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
            'pass_code'=>['required'],
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

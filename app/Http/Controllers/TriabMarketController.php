<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use App\Models\ShopCategory;
use App\Models\State;
use App\Models\Shop;
use App\Models\Product;

class TriabMarketController extends Controller implements HasMiddleware
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
        $categories = ShopCategory::all(); 
        return view('app.triab_market.index', compact('categories'));
    }
 
    /**
     * Show the form for creating a new resource.
     */
    public function show_shops(string $id)
    {
        $state = request()->input('state'); 
        $city = request()->input('city'); 

        $category = ShopCategory::findOrFail($id);
        $shops = Shop::where('category_id', $id); 
        
        if($state)
            $shops->where('state', $state);

        if($city)
            $shops->where('city', $city);

        $shops = $shops->paginate(20);
        return view('app.triab_market.shops.index', compact('shops', 'category'));
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

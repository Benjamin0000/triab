<?php

namespace App\Http\Controllers\Eshop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;
use App\Models\ShopCategory;
use App\Models\State;
use App\Models\Shop;
use App\Models\Product; 
use App\Models\StockHistory; 
use App\Models\Order; 
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class EshopController extends Controller implements HasMiddleware
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
        $user = Auth::user();
        $shops = Shop::where('user_id', $user->id)->latest()->paginate(10); 
        return view('app.eshop.index', compact('shops')); 
    }

    public function products(string $shop_id, string $parent_id=NULL)
    {
        $shop = Shop::findOrFail($shop_id);
        $titles = [];
        $parent = NULL; 
        if($parent_id) {
            $products = Product::where([
                ['shop_id', $shop_id],  
                ['parent_id', $parent_id]
            ])->paginate(20);
            $titles = get_product_category_gen($parent_id);
            $parent = Product::find($parent_id); 
        }else {
            $products = Product::where('shop_id', $shop_id)
            ->whereNull('parent_id')->paginate(20);
        }

        return view('app.eshop.products.index', 
        compact('shop', 'products', 'parent_id', 'titles', 'parent'));
    }

    public function show_create_product_page(string $shop_id, string $parent_id)
    {
        $shop = Shop::findOrFail($shop_id); 
        $parent = Product::findOrFail($parent_id); 
        $titles = [];
        $titles = get_product_category_gen($parent_id);
        return view('app.eshop.products.add_product', compact('shop', 'parent', 'titles')); 
    }

    public function show_edit_product_page(string $product_id)
    {
        $product = Product::findOrFail($product_id); 
        $titles = [];
        $titles = get_product_category_gen($product->parent_id);

        return view('app.eshop.products.edit_product', compact('product', 'titles')); 
    }

    public function add_product_category(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $logo = $request->file('logo');
        $upload = upload_poster($logo, 100);
        if(isset($upload['error'])) return $upload;

        $data = $request->all(); 
        $data['images'] = $upload['path']; 
        Product::create($data);
        return ['success'=>"Category created"]; 
    }

    public function update_product_category(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $data = $request->all();
        $product = Product::findOrFail($data['id']);

        if($logo = $request->file('logo')){
            $upload = upload_poster($logo, 100);
            if(isset($upload['error'])) return $upload;

            $data['images'] = $upload['path'];

            $old_logo = $product->getPosterImage();

            if($old_logo){
                if(Storage::disk('public')->exists($old_logo)){
                    Storage::disk('public')->delete($old_logo);
                    $product->removeImage($old_logo); 
                }
            }

        }
        $product->update($data);
        return ['success'=>"Category updated"]; 
    }

    public function add_product(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'cost_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:100', // Validate each image
        ]);

        $cost_price = (float)$request->cost_price; 
        $selling_price = (float)$request->selling_price; 

        // $description = $request->input('description'); 

        // if(strlen($description) <= 0)
        //     return ['error'=>"Please Enter some description"];

        $shop_id = $request->shop_id; 

        $valid_shop = Shop::where([
            ['id', $shop_id], 
            ['user_id', Auth::id()]
        ])->exists();

        if(!$valid_shop)
            return ['error'=>"Invalid operation"];


        if($cost_price > $selling_price)
            return ['error'=>'The selling price must be higher than the cost price']; 

        $images = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = upload_poster($image, 100); 
                $images[] = $path['path'];
            }
        }

        $product = Product::create([
            'name' => $request->name,
            'cost_price' => $cost_price,
            'selling_price' => $selling_price,
            'description' => $request->description,
            'images' => $images, // Store images as a comma-separated string
            'shop_id' => $shop_id,
            'parent_id' => $request->parent_id,
            'type'=>PRODUCT
        ]);

        return ['success'=>"Product added"]; 
    }

    public function update_product(Request $request, $product_id)
    {
        $request->validate([
            'name' => 'required|string',
            'cost_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'description' => 'required|max:30000',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:100', // Validate each image
            'existingImages' => 'array', // Validate existing images
        ]);
    
        $cost_price = (float)$request->cost_price;
        $selling_price = (float)$request->selling_price;
    
        // Validate price logic
        if ($cost_price > $selling_price) {
            return ['error' => 'The selling price must be higher than the cost price'];
        }
    
        // Validate shop ownership
        $shop_id = $request->shop_id;
        $valid_shop = Shop::where([
            ['id', $shop_id],
            ['user_id', Auth::id()]
        ])->exists();
    
        if (!$valid_shop) {
            return ['error' => "Invalid operation"];
        }
    
        // Fetch product and validate ownership
        $product = Product::where('id', $product_id)->where('shop_id', $shop_id)->first();
        if (!$product) {
            return ['error' => "Product not found or you don't have permission to edit this product"];
        }
    
        $oldImages = $product->images;
        // Handle new images
        $newImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = upload_poster($image, 100);
                $newImages[] = $path['path'];
            }
        }
    
        // Combine existing images and new images
        $existingImages = $request->existingImages ?? [];
        $allImages = array_merge($existingImages, $newImages);
    
        // Update product details
        $product->update([
            'name' => $request->name,
            'cost_price' => $cost_price,
            'selling_price' => $selling_price,
            'description' => $request->description,
            'images' => $allImages, // Update images
        ]);
    
        // Remove images not in the existingImages array
        $imagesToRemove = array_diff($oldImages, $existingImages);
    
        foreach ($imagesToRemove as $image) {
            if(Storage::disk('public')->exists($image)){
                Storage::disk('public')->delete($image);
            }
        }
        return ['success' => "Product updated successfully"];
    }

    public function delete_product(string $id)
    {
        $product = Product::findOrFail($id);

        if($product->shop->user_id != Auth::id())
            return back()->with('error', 'Unauthorized');

        if($product->type == CATEGORY){
            if($product->has_children())
                return back()->with('error', 'This category is not empty');
        }else{
            if($product->total > $product->sold)
                return back()->with('error', 'Please set product stock to 0 first');
        }
        delete_poster($product->images); 
        $product->delete();
        return back()->with('success', 'Product Deleted'); 
    }

    public function add_stock(Request $request, $product_id)
    {
        $request->validate([
            'qty' => ['required', 'numeric', 'min:1'],
            'reason' => ['required', 'string', Rule::in(['New Stock', 'Stock Increase'])],
        ]);
    
        $product = Product::find($product_id);
    
        if (!$product) {
            return back()->with('error', 'Product not found'); 
        }
    
        $qty = (int)$request->qty;
        $reason = $request->reason;

        if($qty <= 0)
            return back();
    
        // Update product total stock
        $product->total += $qty;
        $product->save();
    
        // Record stock history
        StockHistory::create([
            'product_id' => $product->id,
            'amt' => $qty,
            'type' => 1, // 1 for credit/addition
            'desc' => $reason,
        ]);

        return back()->with('success', 'Stock added successfully'); 
    }
    
    public function remove_stock(Request $request, $product_id)
    {
        $request->validate([
            'qty' => ['required', 'numeric', 'min:1'],
            'reason' => ['required', 'string', Rule::in(['Damage', 'For Use', 'Excess'])],
        ]);
    
        $product = Product::find($product_id);
    
        if (!$product) {
            return back()->with('error', 'Product not found');
        }
    
        $qty = (int)$request->qty;
        $reason = $request->reason;

        if($qty <= 0)
            return back();
    
        if ($qty > $product->total) {
            return back()->with('error', 'Insufficient stock');
        }
    
        // Update product total stock
        $product->total -= $qty;
        $product->save();
    
        // Record stock history
        StockHistory::create([
            'product_id' => $product->id,
            'amt' => $qty,
            'type' => 0, // 0 for debit/subtraction
            'desc' => $reason,
        ]);

        return back()->with('success', 'Stock removed successfully');
    }
    
    public function stock_history(string $id)
    {
        $product = Product::findOrFail($id);
    
        // Ensure the authenticated user owns the shop
        if ($product->shop->user_id != Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }
    
        // Default date as today
        $defaultDate = date('Y-m-d');
    
        // Get dates from the request, defaulting to today if not provided
        $from_date = request()->input('from_date', $defaultDate);
        $to_date = request()->input('to_date', $defaultDate);
    
        // Validate date order
        if ($from_date > $to_date) {
            return back()->with('error', "The 'From Date' cannot be higher than the 'To Date'");
        }
    

        // Fetch stock histories within the date range
        $histories = StockHistory::where('product_id', $id)
            ->whereDate('created_at', '>=', $from_date)
            ->whereDate('created_at', '<=', $to_date)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    
        // Calculate stock_in (credit) and stock_out (debit) within the date range
        $stock_in = StockHistory::where('product_id', $id)
            ->where('type', 1) // Credit
            ->whereDate('created_at', '>=', $from_date)
            ->whereDate('created_at', '<=', $to_date)
            ->sum('amt');
    
        $stock_out = StockHistory::where('product_id', $id)
            ->where('type', 0) // Debit
            ->whereDate('created_at', '>=', $from_date)
            ->whereDate('created_at', '<=', $to_date)
            ->sum('amt');
    
        // Calculate stocks_left
        $stocks_left = $stock_in - $stock_out;
    
        // Pass data to the view
        return view('app.eshop.products.stock.history', compact('histories', 'product', 'from_date', 'to_date', 'stock_in', 'stock_out', 'stocks_left'));
    }
    
    

    public function show(string $id)
    {
        $user = Auth::user(); 
        $shop = Shop::where([
            ['id', $id],
            ['user_id', $user->id]
        ])->first(); 

        $sales = Order::selectRaw('
            SUM(CASE WHEN DATE(created_at) = CURDATE() THEN sub_total ELSE 0 END) AS todays_sales,
            SUM(sub_total) AS total_sales,
            SUM(CASE WHEN DATE(created_at) = CURDATE() THEN (sub_total - sub_total_cp) ELSE 0 END) AS todays_profit,
            SUM(sub_total - sub_total_cp) AS total_profit
        ')
        ->where('shop_id', $shop->id) // Apply the constraint
        ->first();
        $totalProduct = Product::where('shop_id', $shop->id)->sum('total');

        $orders = Order::where('shop_id', $shop->id)->latest()->paginate(10);
        return view('app.eshop.show', compact('shop', 'sales', 'totalProduct', 'orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ShopCategory::all(); 
        return view('app.eshop.create', compact('categories')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>['required', 'max:100'], 
            'category'=>['required', 'integer'],
            'logo'=>['required', 'mimes:jpeg,png,jpg,gif,webp'], 
            'description'=>['required'], 
            'state'=>['required'], 
            'city'=>['required'], 
            'address'=>['required', 'string', 'max:500']
        ]);
        $user = Auth::user(); 

        $category_id = $request->input('category');
        $state = $request->input('state');
        $city = $request->input('city');
        $description = $request->input('description');
        $address = $request->input('address');
        $name = $request->input('name'); 

        $category = ShopCategory::find($category_id);
        $validState = validateStateWithCity($state, $city);

        if(!$category)
            return ['error'=>"Invalid category"]; 

        if(!$validState)
            return ['error'=>"Invalid state or city"]; 

        if(strlen($description) > 1000)
            return ['error'=>"Description too long, make it 250 characters max"];

        $file = $request->file('logo'); 
        $upload = upload_poster($file, 100); 

        if( isset($upload['error']) )
            return $upload;

        $logo = $upload['path'];

        Shop::create([
            'user_id'=>$user->id,
            'storeID'=>genStoreID(),
            'category_id'=>$category->id,
            'logo'=>$logo,
            'name'=>$name,
            'state'=>$state,
            'city'=>$city,
            'address'=>$address,
            'description'=>$description
        ]); 

        return ['success'=>'Shop created']; 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user(); 
        $shop = Shop::where([
            ['id', $id],
            ['user_id', $user->id]
        ])->first(); 

        if(!$shop)
            return back()->with('error', "Invalid shop"); 

        $categories = ShopCategory::all(); 

        $state = State::where('name', $shop->state)->first();
        $cities = State::where('parent_id', $state->id)->get();

        return view('app.eshop.edit', compact('shop', 'categories', 'cities')); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'=>['required', 'max:100'], 
            'category'=>['required', 'integer'],
            'logo'=>['nullable', 'mimes:jpeg,png,jpg,gif,webp'], 
            'description'=>['required'], 
            'state'=>['required'], 
            'city'=>['required'], 
            'address'=>['required', 'string', 'max:500']
        ]);

        $user = Auth::user(); 

        $shop = Shop::where([
            ['id', $id], 
            ['user_id', $user->id], 
        ])->first(); 

        if(!$shop)
            return ['error'=>"Invalid shop"]; 
        

        $category_id = $request->input('category');
        $state = $request->input('state');
        $city = $request->input('city');
        $description = $request->input('description');
        $address = $request->input('address');
        $name = $request->input('name'); 

        $category = ShopCategory::find($category_id);
        $validState = validateStateWithCity($state, $city);

        if(!$category)
            return ['error'=>"Invalid category"]; 

        if(!$validState)
            return ['error'=>"Invalid state or city"]; 

        if(strlen($description) > 1000)
            return ['error'=>"Description too long, make it 250 characters max"];

        $file = $request->file('logo'); 

        $logo = ""; 

        if($file){
            $upload = upload_poster($file, 100); 

            if( isset($upload['error']) )
                return $upload;

            $logo = $upload['path'];

            if(Storage::disk('public')->exists($shop->logo))
                Storage::disk('public')->delete($shop->logo);
        }

        $shop->update([
            'category_id'=>$category->id,
            'logo'=>$logo ? $logo : $shop->logo,
            'name'=>$name,
            'state'=>$state,
            'city'=>$city,
            'address'=>$address,
            'description'=>$description
        ]); 

        return ['success'=>"Shop updated"]; 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shop = Shop::findOrFail($id); 

        if(Storage::disk('public')->exists($shop->logo))
            Storage::disk('public')->delete($shop->logo);

        //delete staffs under shop
        //delete product under the shop too. 

        $shop->delete(); 
        return back()->with('success', 'Shop deleted');
    }

    public function set_fee(Request $request)
    {
        $request->validate([
            'shop_id'=>['required'],
            'vat'=>['required'],
            'service_fee'=>['required']
        ]);

        $shop_id = $request->input('shop_id');
        $vat = (float)$request->input('vat');
        $service_fee = (float)$request->input('service_fee');

        $shop = Shop::where([
            ['id', $shop_id], 
            ['user_id', Auth::id()]
        ])->first(); 

        if(!$shop)
            return back(); 
        
        $shop->vat = $vat;
        $shop->service_fee = $service_fee; 
        $shop->save();

        return back()->with('success', 'Fees Set');
    }
}

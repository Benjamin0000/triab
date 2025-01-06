<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Shop; 
use App\Models\Product; 
use App\Models\Order; 
use App\Models\Cart; 
use App\Models\Staff; 


class PosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        $shop = Shop::findOrFail($id);
        $products = Product::where('shop_id', $id)->whereNull('parent_id')->get();
        return view('pos.sales', compact('shop', 'products'));
    }

    public function get_data(string $shop_id, string $id = null)
    {
        if(empty($id) || $id == "null"){
            $products = Product::where('shop_id', $shop_id)->whereNull('parent_id')->get();
        }else{
            $products = Product::where([
                ['shop_id', $shop_id], 
                ['parent_id', $id]
            ])->get();
        }
        return $products->all(); 
    }

    public function save_order(Request $request)
    { 
        $token = $request->bearerToken();
        $shop_id = $request->input('shop_id');
        $pay_method = $request->input('pay_method');
        $cart = $request->input('cart'); 

        $stockCheck = validate_products_in_cart($cart); 
        if(!empty($stockCheck)){
            return ['stocks'=>$stockCheck]; 
        }

        $shop = Shop::find($shop_id);
        if(!$shop)
            return ['error'=>"Invalid Operation"];

        $staff = Staff::where([
            ['shop_id', $shop_id], 
            ['token', $token]
        ])->first();

        if(!$staff)
            return ['error'=>"Unauthorized operation"];

        $sub_total = sum_total($cart);
        $total = $sub_total + $shop->service_fee + calculate_pct($sub_total, $shop->vat);

        $order = Order::create([
            'shop_id'=>$shop_id, 
            'orderID'=>generateReceiptNumber(), 
            'staff'=>$staff->name, 
            'sub_total'=>$sub_total,
            'vat'=>$shop->vat, 
            'fee'=>$shop->service_fee,
            'total'=>$total,
            'pay_method'=>$pay_method
        ]);
        //save to cart 

        foreach($cart as $item){
            $product = Product::find($item['id']); 
            if($product){
                $product->setPosSalesHistory($item['qty']); 
            }
            $orderItem = new Cart();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item['id'];
            $orderItem->name = $item['name'];
            $orderItem->price = $item['price'];
            $orderItem->qty = $item['qty'];
            $orderItem->save();
        }
        //generate recept for printing. 
        $receipt = view('pos.order_receipt', compact('order'))->render();
        return [
            'receipt' => $receipt
        ]; 
    }

    public function search_product(string $shop_id, string $name='')
    {
        if(empty($name) || $name == "null"){
            $products = Product::where('shop_id', $shop_id)->whereNull('parent_id')->get();
        }else{
            $products = Product::where('shop_id', $shop_id)
            ->where('name', 'like', '%' . $name . '%')
            ->get();
        }
        return $products->all(); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function login_page()
    {
        return view('pos.login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function login(Request $request)
    {
        $request->validate([
            'shop_id'=>['required'], 
            'pass_code'=>['required']
        ]);
        $shop_id = $request->input('shop_id');
        $pass_code = $request->input('pass_code');

        $shop =  Shop::where('storeID', $shop_id)->first();
        if(!$shop)
            return ['error'=>"Shop not registered"];

        $staff = Staff::where([
            ['shop_id', $shop->id], 
            ['pass_code', $pass_code]
        ])->first();

        if(!$staff)
            return ['error'=>"Invalid Credentials"];

        if($staff->status == 0)
            return ['error'=>"Your access has been Denied"];

        $token = Str::random(32);
        $staff->token = $token;
        $staff->save();
        return [
            'id'=>$shop->id, 
            'shop_id'=>$shop->storeID,
            'token'=>$token,
            'admin_name'=>$staff->name 
        ]; 
    }

    public function check_auth(string $shop_id)
    {
        $token = request()->bearerToken();
        if ($token) {
            $exists = Staff::where([
                ['shop_id', $shop_id], 
                ['token', $token], 
                ['status', 1]
            ])->first();

            if($exists)
                return ['signdIn'=>true];
        }
        return ['signdIn'=>false]; 
    }

}
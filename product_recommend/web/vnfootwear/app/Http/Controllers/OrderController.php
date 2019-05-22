<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\OrderDetail;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getMany(Request $request)
    {
        $limit = $request->limit;
        $offset = $request->offset;
        $name = $request->name;
        $phone = $request->phone;  
        $address = $request->address;
        $status = $request->status;

        $matches = array();
        if ($name) {
            $matches["name"] = $name;
        }
        if ($phone) {
            $matches["phone"] = $phone;
        }
        if ($address) {
            $matches["address"] = $address;
        }
        if ($status) {
            $matches["status"] = $status;
        }

        $orders = Order::with(['promo','order_details','order_details.product'])
        ->where($matches)
        ->customPaginate($limit, $offset)->get();
    

        $total = Order::where($matches)
        ->count();

        return [$orders, $total];
    }

    public function get($id)
    {
        $order = Order::find($id);
        return $order;
    }

    public function add(Request $request)
    {
        $order_session = $request->session()->all();
        $order = new Order;

        $order->user_id = Auth::user()->id;
        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->address = $request->address;
        if ($request->session()->has('promo_id')) {
            $order->promotion_id = $order_session['promo_id'];
        } else {
            $order->promotion_id = null;
        }
        $order->save();

        foreach($order_session['products'] as $product) {
            $order_detail = new OrderDetail;
            $order_detail->product_id = $product['id'];
            $order_detail->order_id = $order->id;
            $order_detail->quantity = $product['quantity'];
            $order_detail->size = $product['size'];
            $order_detail->color = $product['color'];
    
            $order_detail->save();
        }

        $request->session()->put('discount', 0);
        $request->session()->forget('promo_id');
        $request->session()->forget('products');
        $request->session()->save();

        return response()->json($order, 201);
    }

    public function update($id, Request $request)
    {
        $order = Order::find($id);
        $order->status = $request->status;
        $order->save();

        return response()->json($order, 201);
    }
}

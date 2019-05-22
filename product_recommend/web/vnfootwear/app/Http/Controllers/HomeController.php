<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\User;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use App\Recommendation;

class HomeController extends Controller
{
    public $session;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Store $session)
    {
        $this->session = $session;
        // $this->middleware('auth');
    }

    public function home()
    {
        return view('frontend.index');
    }

    public function products_list(Request $request)
    {
        $name = $request->name;
        $category = $request->category;
        $genre = $request->genre;    
        $id = Auth::id();
        if ($id) {
            $recs = Recommendation::select('recommended_products')
            ->where('user_id', $id)
            ->first();
            $rec_prod_ids = $recs->recommended_products;
            $rec_prod_id_arr = explode("|", $rec_prod_ids);
            $rec_prods = Product::find($rec_prod_id_arr);
            return view('frontend.list', [
                'name' => $name, 
                'category' => $category, 
                'genre' => $genre, 
                'recs' => $rec_prods
            ]);
        } 
        return view('frontend.list', ['name' => $name, 'category' => $category, 'genre' => $genre]);
    }

    public function detail($code)
    {
        $product = Product::where('code', $code)->first();
        return view('frontend.detail', ['id' => $product->id]);
    }

    public function cart()
    {
        // $this->session->flush();
        // $this->session->save();
        // dd($this->session->all());
        return view('frontend.cart.cart');
    }

    public function add_cart(Request $request)
    {
        $this->session->put('discount', 0);
        $this->session->forget('promo_id');
        $order = json_decode($request->order, true);
        $product = json_decode($request->product, true);
        $order['image'] = $product['big_image'];
        $order['genre'] = $product['genre'];
        $order['price'] = $product['price'];
        $order['name'] = $product['name'];
        $order['code'] = $product['code'];
        $order['id'] = $product['id'];
        $key = 'products.' . $product['id'].$order['color'].$order['size'];
        $order['key'] = $key;
        if ($this->session->has($key)) {
            $order_detail = $this->session->pull($key);
            $order_detail['quantity'] = $order_detail['quantity'] + $order['quantity'];
            $this->session->put($key, $order_detail);
            $this->session->save();
        } else {
            $this->session->put($key, $order);
            $this->session->save();
        }

        $products = $this->session->get('products');
        $total_products = 0;
        $total_price = 0;
        if ($products) {
            foreach($products as $product) {
                $total_products += $product['quantity'];
                $total_price += $product['quantity'] * $product['price'];
            }
            $this->session->put('total_products', $total_products);
            $this->session->put('total_price', $total_price);
            $this->session->save();
        }        
        
        return $total_products;
    }

    public function remove_cart(Request $request)
    {
        $this->session->put('discount', 0);
        $this->session->forget('promo_id');
        $this->session->forget($request->key);
        $products = $this->session->get('products');
        $total_products = 0;
        $total_price = 0;
        if ($products) {
            foreach($products as $product) {
                $total_products += $product['quantity'];
                $total_price += $product['quantity'] * $product['price'];
            }
            $this->session->put('total_products', $total_products);
            $this->session->put('total_price', $total_price);
            $this->session->save();
        } else {
            $this->session->put('total_products', $total_products);
            $this->session->put('total_price', $total_price);
            $this->session->save();
        }   
        return response()->json("success", 201);
    }

    public function use_promotion(Request $request)
    {
        $this->session->put('discount', $request->promo);
        $this->session->put('promo_id', $request->promo_id);
        $this->session->save();
        return response()->json("success", 201);
    }

    public function checkout(Request $request)
    {
        // dd($request->session()->all());
        return view('frontend.order.checkout');
    }

    public function success(Request $request)
    {
        return view('frontend.order.announce');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    // User
    public function users()
    {
        return view('user.user');
    }

    public function profiles($username, Request $request)
    {
        $user = User::where('username', $username)->first();
        return view('user.user_profile', ['user_id' => $user->id, 'username' => $username]);
    }

    public function profiles_edit($username, Request $request)
    {
        $user = User::where('username', $username)->first();
        return view('user.user_profile_edit', ['user_id' => $user->id, 'method' => 'edit', 'username' => $username]);
    }

    public function add()
    {
        return view('user.user_add');
    }

    //Post
    public function products()
    {
        return view('product.product');
    }

    public function add_product()
    {
        return view('product.add_product');
    }

    public function edit_product($id)
    {
        $product = Product::find($id);
        return view('product.edit_product', ['product' => $product]);
    }

    public function promotions()
    {
        return view('promotion.promotion');
    }
    
    public function orders()
    {
        return view('order.order');
    }
}

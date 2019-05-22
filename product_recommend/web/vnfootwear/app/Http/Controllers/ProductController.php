<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;
use Cloudder;
use App\Product;

class ProductController extends Controller
{
    public function getMany(Request $request)
    {
        $limit = $request->limit;
        $offset = $request->offset;
        $name = $request->name;
        $code = $request->code;
        $category = $request->category;
        $genre = $request->genre;        

        $matches = array();
        if ($name) {
            $matches["name"] = $name;
        }
        if ($code) {
            $matches["code"] = $code;
        }
        if ($category) {
            $matches["category"] = $category;
        }
        if ($genre) {
            $matches["genre"] = $genre;
        }
        
        $products = Product::where($matches)
        ->orderBy('created_at', 'desc')->customPaginate($limit, $offset)->get();
    

        $total = Product::where($matches)
        ->count();

        return [$products, $total];
    }

    public function get($id)
    {
        $product = Product::find($id);
        return $product;
    }

    public function add(Request $request)
    {
        $image_public_id = '';
        if($request->get('image'))
        {
            $image_public_id = Uuid::generate()->string;
            $image = $request->get('image');
            Cloudder::upload($image, $image_public_id);
        }
        
        $product = new Product;

        $product->name = $request->name;
        $product->category = $request->category;
        $product->genre = $request->genre;
        $product->code = $request->code;
        $product->size = $request->size;
        $product->color = $request->color;
        $product->price = $request->price;
        $product->image = $image_public_id;

        $product->save();

        return response()->json($product, 201);
    }

    public function update($id, Request $request)
    {
        $image_public_id = '';
        if ($request->thumbnail_is_new) {
            if($request->get('image'))
            {
                $image_public_id = Uuid::generate()->string;
                $image = $request->get('image');
                Cloudder::upload($image, $image_public_id);
            }
        }

        $product = Product::find($id);
        $product->name = $request->product['name'];
        $product->category = $request->product['category'];
        $product->genre = $request->product['genre'];
        $product->code = $request->product['code'];
        $product->size = $request->product['size'];
        $product->color = $request->product['color'];
        $product->price = $request->product['price'];
        if ($image_public_id != '') {
            $product->image = $image_public_id;
        }
        $product->save();

        return response()->json($product, 201);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return response()->json(null, 204);
    }

}

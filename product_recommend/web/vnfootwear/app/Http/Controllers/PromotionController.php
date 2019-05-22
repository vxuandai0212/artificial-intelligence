<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Promotion;

class PromotionController extends Controller
{
    public function getMany(Request $request)
    {
        $limit = $request->limit;
        $offset = $request->offset;
        $code = $request->code;
        $discount = $request->discount;  

        $matches = array();
        if ($code) {
            $matches["code"] = $code;
        }
        if ($discount) {
            $matches["discount"] = $discount;
        }

        $promotions = Promotion::where($matches)
        ->customPaginate($limit, $offset)->get();
    

        $total = Promotion::where($matches)
        ->count();

        return [$promotions, $total];
    }

    public function get($id)
    {
        $promotion = Promotion::find($id);
        return $promotion;
    }

    public function add(Request $request)
    {
        $promotion = new Promotion;

        $promotion->code = $request->code;
        $promotion->discount = $request->discount;

        $promotion->save();

        return response()->json($promotion, 201);
    }

    public function update($id, Request $request)
    {
        $promotion = Promotion::find($id);
        $promotion->code = $request->code;
        $promotion->discount = $request->discount;
    
        $promotion->save();

        return response()->json($promotion, 201);
    }

    public function destroy($id)
    {
        $promotion = Promotion::find($id);
        $promotion->delete();

        return response()->json(null, 204);
    }

}

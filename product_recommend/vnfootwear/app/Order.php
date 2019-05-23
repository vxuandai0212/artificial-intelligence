<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'name', 
        'phone',
        'address',
        'status', 
        'promotion_id'
    ];

    protected $appends = [
        'total_quantity',
        'original_price',
        'total_price'
    ];

    public function scopeCustomPaginate($query, $limit, $offset)
    {
        if ($offset != 0) {
            return $query->skip($offset)->take($limit);
        } else {
            return $query->take($limit);
        }
    }

    public function promo()
    {
        return $this->hasOne('App\Promotion', 'id', 'promotion_id');
    }

    public function order_details()
    {
        return $this->hasMany('App\OrderDetail', 'order_id', 'id');
    }

    public function getTotalQuantityAttribute()
    {       
        return OrderDetail::where('order_id', $this->id)->sum('quantity');
    }

    public function getOriginalPriceAttribute()
    {   
        $original_price = 0;
        $orders = OrderDetail::with('product')->where('order_id', $this->id)->get();
        foreach($orders as $order) {
            $original_price += $order->quantity * $order->product->price;
        }
        return $original_price;
    }

    public function getTotalPriceAttribute()
    {   
        $discount;
        if ($this->promo === null) {
            $discount = 0;
        } else {
            $discount = $this->promo->discount;
        }
        $total_price = $this->original_price - ($this->original_price * $discount / 100);
        return $total_price;
    }

}

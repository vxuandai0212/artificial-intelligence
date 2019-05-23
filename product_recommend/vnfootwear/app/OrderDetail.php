<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    //product_id	order_id	quantity
    protected $fillable = [
        'product_id',
        'order_id', 
        'quantity'
    ];

    public function product()
    {
        return $this->hasOne('App\Product', 'id', 'product_id');
    }

    public function scopeCustomPaginate($query, $limit, $offset)
    {
        if ($offset != 0) {
            return $query->skip($offset)->take($limit);
        } else {
            return $query->take($limit);
        }
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'code',
        'discount'
    ];

    public $timestamps = false;

    public function scopeCustomPaginate($query, $limit, $offset)
    {
        if ($offset != 0) {
            return $query->skip($offset)->take($limit);
        } else {
            return $query->take($limit);
        }
    }

}

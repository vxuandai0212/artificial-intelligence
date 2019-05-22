<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cloudder;

class Product extends Model
{
    protected $fillable = [
        'name', 
        'code',
        'category', 
        'genre',
        'size',
        'color',
        'image',
        'price'
    ];

    protected $appends = [
        'images',
        'big_image',
        'thumbnail',
        'big_thumbnail',
        'edit_url',
        'view_url'
    ];

    public function scopeCustomPaginate($query, $limit, $offset)
    {
        if ($offset != 0) {
            return $query->skip($offset)->take($limit);
        } else {
            return $query->take($limit);
        }
    }

    public function getImagesAttribute()
    {
        $options = array("width" => 60, "height" => 60);
            
        return Cloudder::show($this->image, $options);
    }

    public function getBigImageAttribute()
    {        
        return Cloudder::show($this->image);
    }

    public function getThumbnailAttribute()
    {        
        $options = array("width" => 250, "height" => 250);
        return Cloudder::show($this->image, $options);
    }

    public function getBigThumbnailAttribute()
    {        
        $options = array("width" => 500, "height" => 500);
        return Cloudder::show($this->image, $options);
    }

    public function getEditUrlAttribute()
    {
        return '/products/edit/'.$this->id;
    }

    public function getViewUrlAttribute()
    {
        return '/products/product-detail/'.$this->code;
    }
}

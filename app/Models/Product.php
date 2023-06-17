<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable =['code_id','brand','model','common_name','img_id','description','product_code','price'];

    public function category(){
        return $this->belongsTo('App\Models\Category', 'code_id');
    }
    public function ratings(){
        return $this->hasMany('App\Models\Rating', 'product_id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ProductImage');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable =['subcategory_id','brand','model','common_name','img_id','description','product_code','price','sku','size','warrant','availability','dimension','bar_code','discount','tags','offset','options'];

    public function subcategory(){
        return $this->belongsTo('App\Models\subcategory', 'subcategory_id');
    }
    public function ratings(){
        return $this->hasMany('App\Models\Rating', 'product_id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ProductImage');
    }
    public function stock()
    {
        return $this->hasMany('App\Models\Stock');
    }
}

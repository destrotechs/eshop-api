<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','subcategory_code','subcategory_name'];

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }
    public function products(){
        return $this->hasMany('App\Models\Product','subcategory_id');
    }
}

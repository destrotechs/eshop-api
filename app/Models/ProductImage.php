<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    protected $fillable =['img_url'];


    public function product(){
        return $this->belongsTo('App\Product', 'img_id');
    }
}

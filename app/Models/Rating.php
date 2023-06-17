<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $fillable = ['product_id','user_id','star_rate','review'];

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}

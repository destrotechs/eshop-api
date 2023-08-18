<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','quanty_added','unit_of_measure','date_altered'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}

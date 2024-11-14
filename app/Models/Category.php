<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable =['category_code','category_name','image_path'];

    public function subcategories()
    {
        return $this->hasMany('App\Models\subcategory');
    }
}

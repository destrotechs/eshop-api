<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','preferred_payment','phone_number','card_number','cvv','locale'];

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}

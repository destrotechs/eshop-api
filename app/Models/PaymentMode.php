<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMode extends Model
{
    use HasFactory;
    protected $fillable = ['payment_mode_details','payment_mode_name'];

    /*
    * Get the user that owns the PaymentMode
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'role_users', 'payment_mode_id', 'user_id');
    }
    public function payments(){
        return $this->hasMany('App\Models\Payment');
    }
    
}

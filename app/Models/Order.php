<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['order_number','user_id','total_cost','vat','discount','served_by','address_id','status'];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function items(){
        return $this->hasMany('App\Models\OrderItem','order_id');
    }
    public function address(){
        return $this->belongsTo('App\Models\Address','address_id');
    }
    public function payment(){
        return $this->belongsTo('App\Models\Payment','order_id');
    }
    public function payment_mode(){
        return $this->belongsTo('App\Models\PaymentMode','payment_mode_id');
    }

}

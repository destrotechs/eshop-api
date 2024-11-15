<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [ 'order_id','amount','currency','payment_mode_id','payment_id','paid_on','payment_details'];

    public function order(){
        return $this->belongsTo('App\Models\Order','order_id');
    }
    public function payment_mode(){
        return $this->belongsTo('App\Models\PaymentMode','payment_mode_id');
    }
}

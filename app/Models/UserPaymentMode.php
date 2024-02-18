<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPaymentMode extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','payment_mode_id'];
}

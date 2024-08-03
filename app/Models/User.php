<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function profile(){
        return $this->hasOne('App\Models\Profile','user_id');
    }

    public function orders(){
        return $this->hasMany('App\Models\Order','user_id');
    }
    public function addresses(){
        return $this->hasMany('App\Models\Address','user_id');
    }
    
    
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_users', 'user_id', 'role_id');
    }
    /**
     * Get all of the payment_modes for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payment_modes()
    {
        return $this->belongsToMany('App\Models\PaymentMode', 'user_payment_modes', 'user_id', 'payment_mode_id');
    }
    public function hasRole($role)
    {
        return $this->roles()->where('role_name', $role)->exists();
    }
}

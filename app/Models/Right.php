<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Right extends Model
{
    use HasFactory;
    protected $fillable = ['right_to'];
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_rights', 'right_id', 'role_id');
    }
}

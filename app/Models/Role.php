<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['role_name'];

    /**
     * The rights that belong to the Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rights()
    {
        return $this->belongsToMany('App\Models\Right', 'role_rights', 'role_id','right_id');
    }
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'role_users', 'role_id', 'user_id');
    }
    public function hasRight($right)
    {
        return $this->rights()->where('right_to', $right)->exists();
    }
}

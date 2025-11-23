<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';
    protected $fillable = ['title', 'photo_profile', 'description', 'key'];

    public function assignments(){
        return $this->hasMany(GroupAssignments::class, 'group_id');
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'groupuser', 'group_id', 'user_id')->withPivot('isAdmin');
    }
}

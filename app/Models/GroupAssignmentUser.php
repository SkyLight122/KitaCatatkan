<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupAssignmentUser extends Model
{
    protected $fillable = [
        'status_id',
        'user_id',
        'group_assignment_id'
    ];
}

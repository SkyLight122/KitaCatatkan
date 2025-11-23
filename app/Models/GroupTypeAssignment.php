<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupTypeAssignment extends Model
{
    protected $fillable = [
        'group_id',
        'category',
        'color',
    ];
}

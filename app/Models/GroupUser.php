<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    protected $table = 'groupuser';

    protected $fillable = [
        'group_id',
        'user_id',
        'isAdmin',
    ];

    public $timestamps = false; // pivot biasanya tanpa timestamps
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    protected $fillable = ['user_id', 'name', 'path'];

    /**
     * Get the user that owns the category
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

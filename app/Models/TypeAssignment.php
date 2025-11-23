<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypeAssignment extends Model
{
    protected $fillable = ['user_id', 'category', 'color'];

    /**
     * Get the user that owns the type assignment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

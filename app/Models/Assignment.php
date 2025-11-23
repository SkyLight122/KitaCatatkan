<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    protected $casts = [
        'status_id' => 'integer',
        'priority_id' => 'integer',
    ];
    
    protected $fillable = ['user_id', 'title', 'category_id', 'type_assignment_id', 'description', 'status_id', 'priority_id', 'due_date'];

    /**
     * Get the user that owns the assignment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category for the assignment
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the type assignment for the assignment
     */
    public function typeAssignment(): BelongsTo
    {
        return $this->belongsTo(TypeAssignment::class, 'type_assignment_id');
    }

    /**
     * Get the priority for the assignment
     */
    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }

    /**
     * Get the status for the assignment
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}

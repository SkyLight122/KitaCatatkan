<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalAssignment extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category_id',
        'type_id',
        'priority_id',
        'status_id',
        'due_date',
    ];

    public function category() { return $this->belongsTo(PersonalCategory::class); }
    public function type() { return $this->belongsTo(PersonalType::class); }
    public function priority() { return $this->belongsTo(Priority::class); }
    public function status() { return $this->belongsTo(Status::class); }
}


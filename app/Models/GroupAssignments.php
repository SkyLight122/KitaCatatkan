<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupAssignments extends Model
{
    protected $fillable = ['group_id', 'title', 'group_category_id', 'description', 'status_id', 'priority_id', 'group_type_assignment_id', 'due_date'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_assignment_users', 'group_assignment_id', 'user_id')
            ->withPivot('status_id', 'finished_at')
            ->withTimestamps();
    }

    public function category(){
        return $this->belongsTo(GroupCategory::class, 'group_category_id');
    }
    public function priority(){
        return $this->belongsTo(Priority::class, 'priority_id');
    }
    public function typeAssignment(){
        return $this->belongsTo(GroupTypeAssignment::class, 'group_type_assignment_id');
    }
    public function group(){
        return $this->belongsTo(Group::class, 'group_id');
    }
}

<?php

namespace App\Livewire\Assignments;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;
use App\Models\Category;
use App\Models\TypeAssignment;
use App\Models\Priority;
use App\Models\Status;
use App\Models\GroupAssignmentUser;
use Carbon\Carbon;

class Index extends Component
{
    public $search = '';

    public $filterPriority = '';
    public $filterCategory = '';
    public $filterType = '';
    public $filterAssignmentType = ''; // personal or group

    public $sortStatus = '';
    public $sortDeadline = '';
    
    public $filterStatus = ''; // '' = all, 'ongoing' = ongoing, 'finished' = finished

    public $detailOpen = false;
    public $detailData = null;

    public $editOpen = false;
    public $editData = null;
    public $editId = null;
    public $editType = null; // 'personal' or 'group'
    public $editTitle = '';
    public $editDescription = '';
    public $editCategory = '';
    public $editPriority = '';
    public $editType_assignment = '';
    public $editDueDate = '';
    public $editDueTime = '';

    // Add category/type modal properties
    public $showCategoryModal = false;
    public $newCategoryName = '';
    public $newCategoryIcon = '';
    public $showTypeModal = false;
    public $newTypeName = '';
    public $newTypeColor = 100;
    public $icons = [];

    protected $listeners = [
        'task-added' => '$refresh',
    ];

    public function mount()
    {
        $this->loadIcons();
    }

    public function loadIcons()
    {
        $this->icons = array_values(array_diff(scandir(public_path('img/categoryIcons')), ['.', '..']));
    }

    public function openDetail($id)
    {
        $userId = Auth::id();
        $userGroupIds = Auth::user()->groups()->pluck('groups.id')->toArray();

        // Check if it's a personal assignment
        $assignment = Assignment::with(['category', 'priority', 'typeAssignment', 'status'])->find($id);
        if ($assignment && $assignment->user_id === $userId) {
            $this->detailData = $assignment;
            $this->detailOpen = true;
            return;
        }

        // Check if it's a group assignment
        $groupAssignment = \App\Models\GroupAssignments::with(['category', 'priority', 'typeAssignment', 'group'])
            ->whereIn('group_id', $userGroupIds)
            ->find($id);
        if ($groupAssignment) {
            $this->detailData = $groupAssignment;
            $this->detailOpen = true;
            return;
        }
    }

    public function closeDetail()
    {
        $this->detailOpen = false;
        $this->detailData = null;
    }

    public function openEdit($id, $type)
    {
        $this->editType = $type;
        $this->editId = $id;

        if ($type === 'personal') {
            $assignment = Assignment::find($id);
            if ($assignment && $assignment->user_id === Auth::id()) {
                $this->editData = $assignment;
                $this->editTitle = $assignment->title;
                $this->editDescription = $assignment->description;
                $this->editCategory = $assignment->category_id;
                $this->editPriority = $assignment->priority_id;
                $this->editType_assignment = $assignment->type_assignment_id;
                $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                $this->editDueDate = $dueDate->format('Y-m-d');
                $this->editDueTime = $dueDate->format('H:i');
                $this->editOpen = true;
            }
        }
    }

    public function closeEdit()
    {
        $this->editOpen = false;
        $this->editData = null;
        $this->reset(['editTitle', 'editDescription', 'editCategory', 'editPriority', 'editType_assignment', 'editDueDate', 'editDueTime', 'showCategoryModal', 'showTypeModal', 'newCategoryName', 'newCategoryIcon', 'newTypeName', 'newTypeColor']);
    }

    public function saveEdit()
    {
        $this->validate([
            'editTitle' => 'required|string|max:100',
            'editDescription' => 'required|string',
            'editCategory' => 'required|integer',
            'editPriority' => 'required|integer',
            'editType_assignment' => 'required|integer',
            'editDueDate' => 'required|date',
            'editDueTime' => 'required',
        ]);

        $datetime = $this->editDueDate . ' ' . $this->editDueTime;

        if ($this->editType === 'personal') {
            Assignment::where('id', $this->editId)
                ->where('user_id', Auth::id())
                ->update([
                    'title' => $this->editTitle,
                    'description' => $this->editDescription,
                    'category_id' => $this->editCategory,
                    'priority_id' => $this->editPriority,
                    'type_assignment_id' => $this->editType_assignment,
                    'due_date' => $datetime,
                ]);
        }

        $this->closeEdit();
        $this->dispatch('statusUpdated');
    }

    public function openCategoryModal()
    {
        $this->showCategoryModal = true;
    }

    public function closeCategoryModal()
    {
        $this->showCategoryModal = false;
        $this->newCategoryName = '';
        $this->newCategoryIcon = '';
    }

    public function saveCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|string|max:100',
        ]);

        $category = Category::create([
            'name' => $this->newCategoryName,
            'user_id' => Auth::id(),
            'path' => $this->newCategoryIcon ?? '',
        ]);

        $this->editCategory = $category->id;
        $this->closeCategoryModal();
    }

    public function openTypeModal()
    {
        $this->showTypeModal = true;
    }

    public function closeTypeModal()
    {
        $this->showTypeModal = false;
        $this->newTypeName = '';
        $this->newTypeColor = 100;
    }

    public function saveType()
    {
        $this->validate([
            'newTypeName' => 'required|string|max:100',
            'newTypeColor' => 'required|integer|min:0|max:255',
        ]);

        $type = TypeAssignment::create([
            'category' => $this->newTypeName,
            'color' => $this->newTypeColor,
            'user_id' => Auth::id(),
        ]);

        $this->editType_assignment = $type->id;
        $this->closeTypeModal();
    }

    public function updateStatus($id, $statusId, $assignmentType = null)
    {
        $userId = Auth::id();

        if ($assignmentType === 'personal') {
            // Update personal assignment
            Assignment::where('id', $id)
                ->where('user_id', $userId)
                ->update(['status_id' => $statusId]);
        } elseif ($assignmentType === 'group') {
            // Update group assignment in pivot table
            \App\Models\GroupAssignmentUser::where('group_assignment_id', $id)
                ->where('user_id', $userId)
                ->update(['status_id' => $statusId]);
        } else {
            // Fallback: try personal first, then group
            $personalUpdated = Assignment::where('id', $id)
                ->where('user_id', $userId)
                ->update(['status_id' => $statusId]);

            if (!$personalUpdated) {
                \App\Models\GroupAssignmentUser::where('group_assignment_id', $id)
                    ->where('user_id', $userId)
                    ->update(['status_id' => $statusId]);
            }
        }

        // Dispatch event to refresh notifications
        $this->dispatch('statusUpdated');
    }

    public function render()
    {
        $userId = Auth::id();
        $userGroupIds = Auth::user()->groups()->pluck('groups.id')->toArray();
        
        // Personal assignments
        $personalAssignments = Assignment::with(['category', 'priority', 'typeAssignment', 'status'])
            ->where('user_id', $userId)
            ->when($this->search, fn($q) =>
                $q->where('title', 'like', "%{$this->search}%")
            )
            ->when($this->filterPriority, fn($q) =>
                $q->where('priority_id', $this->filterPriority)
            )
            ->when($this->filterCategory, fn($q) =>
                $q->where('category_id', $this->filterCategory)
            )
            ->when($this->filterType, fn($q) =>
                $q->where('type_assignment_id', $this->filterType)
            )
            ->get()
            ->map(fn($a) => [
                'id' => $a->id,
                'title' => $a->title,
                'due_date' => $a->due_date,
                'priority_id' => $a->priority_id,
                'status_id' => $a->status_id,
                'category' => $a->category,
                'priority' => $a->priority,
                'typeAssignment' => $a->typeAssignment,
                'status' => $a->status,
                'assignment_type' => 'personal',
            ]);
        
        // Group assignments
        $groupAssignments = collect();
        if (!$this->filterAssignmentType || $this->filterAssignmentType === 'group') {
            if ($userGroupIds) {
                $groupAssignments = \App\Models\GroupAssignments::with(['category', 'priority', 'typeAssignment'])
                    ->whereIn('group_id', $userGroupIds)
                    ->when($this->search, fn($q) =>
                        $q->where('title', 'like', "%{$this->search}%")
                    )
                    ->when($this->filterPriority, fn($q) =>
                        $q->where('priority_id', $this->filterPriority)
                    )
                    ->when($this->filterType, fn($q) =>
                        $q->where('group_type_assignment_id', $this->filterType)
                    )
                    ->get()
                    ->map(function($a) use ($userId) {
                        // Get the current user's status from the pivot table
                        $userPivot = \App\Models\GroupAssignmentUser::where('group_assignment_id', $a->id)
                            ->where('user_id', $userId)
                            ->first();
                        
                        $statusId = $userPivot?->status_id ?? 1; // Default to 1 if no pivot record
                        
                        return [
                            'id' => $a->id,
                            'title' => $a->title,
                            'due_date' => $a->due_date,
                            'priority_id' => $a->priority_id,
                            'category' => $a->category,
                            'priority' => $a->priority,
                            'typeAssignment' => $a->typeAssignment,
                            'assignment_type' => 'group',
                            'status_id' => $statusId,
                        ];
                    });
            }
        }
        
        $assignments = $personalAssignments->concat($groupAssignments);
        
        if ($this->filterAssignmentType === 'personal') {
            $assignments = $assignments->where('assignment_type', 'personal');
        } elseif ($this->filterAssignmentType === 'group') {
            $assignments = $assignments->where('assignment_type', 'group');
        }
        
        // Filter by status
        if ($this->filterStatus === 'ongoing') {
            $assignments = $assignments->whereIn('status_id', [1, 2]); // Not completed
        } elseif ($this->filterStatus === 'finished') {
            $assignments = $assignments->where('status_id', 3); // Completed
        }
        // If empty, show all - no filtering needed
        
        // Apply sorting
        if ($this->sortStatus) {
            $assignments = $assignments->sortBy('status_id', SORT_REGULAR, $this->sortStatus === 'desc');
        }
        
        if ($this->sortDeadline) {
            $assignments = $assignments->sortBy('due_date', SORT_REGULAR, $this->sortDeadline === 'desc');
        } else {
            $assignments = $assignments->sortBy('due_date');
        }
        
        return view('livewire.assignments.index', [
            'assignments' => $assignments,
            'categories' => Category::where('user_id', $userId)->get(),
            'types' => TypeAssignment::where('user_id', $userId)->get(),
            'priorities' => Priority::all(),
            'statuses' => Status::all(),
            'icons' => array_values(array_diff(scandir(public_path('img/categoryIcons')), ['.', '..'])),
        ]);
    }
}

<?php

namespace App\Livewire\Assignment;

use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonalAssignment;
use App\Models\PersonalCategory;
use App\Models\PersonalType;
use App\Models\GroupAssignments;
use App\Models\Priority;
use App\Models\Status;
use App\Models\GroupAssignmentUser;

class CombinedTasks extends Component
{
    // SEARCH / FILTER / SORT
    public $search = '';
    public $filterCategory = '';
    public $filterType = '';
    public $filterSource = 'all'; // all | personal | group
    public $sortBy = 'due';
    public $sortDirection = 'asc';

    // MODALS
    public $detailOpen = false;
    public $detailData = null;

    public $openCreateModal = false;
    public $openCategoryModal = false;
    public $openTypeModal = false;

    // CREATE PERSONAL
    public $new_title = '';
    public $new_description = '';
    public $new_category_id = '';
    public $new_type_id = '';
    public $new_priority_id = 1;
    public $new_due_date_date = '';
    public $new_due_date_time = '23:59';

    // ADD CATEGORY
    public $newCategoryName = '';
    public $newCategoryIcon = null;

    // ADD TYPE
    public $newTypeName = '';
    public $newTypeColor = '#0F1627';

    // STATUS DROPDOWN
    public $openStatusFor = null;

    protected $listeners = ['refreshCombined' => '$refresh'];

    // ==============================================
    // PERSONAL TASKS
    // ==============================================
    private function getPersonalTasks(): Collection
    {
        $q = PersonalAssignment::with(['category','type','priority','status'])
            ->where('user_id', Auth::id());

        if ($this->filterCategory) $q->where('category_id', $this->filterCategory);
        if ($this->filterType) $q->where('type_id', $this->filterType);
        if ($this->search) $q->where('title','like', "%{$this->search}%");

        return $q->get()->map(fn($t) => [
            'id' => $t->id,
            'source' => 'personal',
            'title' => $t->title,
            'category' => $t->category?->name,
            'category_icon' => $t->category?->icon,
            'type' => $t->type?->name,
            'type_color' => $t->type?->color,
            'priority' => $t->priority?->name,
            'priority_id' => $t->priority_id,
            'status' => $t->status?->name,
            'status_id' => $t->status_id,
            'due' => $t->due_date,
            'raw' => $t,
        ]);
    }

    // ==============================================
    // GROUP TASKS
    // ==============================================
    private function getGroupTasks(): Collection
    {
        $userId = Auth::id();

        $q = GroupAssignments::with(['category','priority','typeAssignment'])
            ->whereHas('users', fn($q)=>$q->where('user_id',$userId));

        if ($this->search) $q->where('title','like',"%{$this->search}%");

        return $q->get()->map(function($t) use ($userId) {
            $pivot = $t->users()->where('user_id',$userId)->first();
            $statusId = $pivot?->pivot->status_id ?? 1;

            $statusName = [
                1 => 'Belum dimulai',
                2 => 'sedang dikerjakan',
                3 => 'Selesai'
            ][$statusId];

            return [
                'id' => $t->id,
                'source' => 'group',
                'title' => $t->title,
                'category' => $t->category?->name,
                'category_icon' => $t->category?->path,
                'type' => $t->typeAssignment?->category,
                'priority' => $t->priority?->name,
                'priority_id' => $t->priority_id,
                'status' => $statusName,
                'status_id' => $statusId,
                'due' => $t->due_date,
                'raw' => $t,
            ];
        });
    }

    // ==============================================
    // COMBINE + SORT
    // ==============================================
    private function combined(): Collection
    {
        $tasks = collect();

        if ($this->filterSource === 'all' || $this->filterSource === 'personal')
            $tasks = $tasks->merge($this->getPersonalTasks());

        if ($this->filterSource === 'all' || $this->filterSource === 'group')
            $tasks = $tasks->merge($this->getGroupTasks());

        return $this->sortDirection === 'asc'
            ? $tasks->sortBy($this->sortBy)->values()
            : $tasks->sortByDesc($this->sortBy)->values();
    }

    // ==============================================
    // MODAL DETAIL
    // ==============================================
    public function openDetail($item)
    {
        $this->detailData = $item;
        $this->detailOpen = true;
    }

    public function closeDetail()
    {
        $this->detailOpen = false;
    }

    // ==============================================
    // STATUS DROPDOWN
    // ==============================================
    public function openStatusDropdown($id, $source)
    {
        $key = "{$source}_{$id}";
        $this->openStatusFor = $this->openStatusFor === $key ? null : $key;
    }

    public function closeStatusDropdown()
    {
        $this->openStatusFor = null;
    }

    public function updateStatus($id, $source, $statusId)
    {
        $userId = Auth::id();
        
        if ($source === 'personal') {
            // CRITICAL: Must check BOTH id AND user_id to prevent wrong assignment being updated
            PersonalAssignment::where('id', $id)
                ->where('user_id', $userId)
                ->update(['status_id' => $statusId]);
        } else if ($source === 'group') {
            // Update the group assignment user pivot record
            GroupAssignmentUser::where('group_assignment_id', $id)
                ->where('user_id', $userId)
                ->update(['status_id' => $statusId]);
        }

        $this->openStatusFor = null;
    }

    // ==============================================
    // CREATE CATEGORY
    // ==============================================
    public function createCategory()
    {
        PersonalCategory::create([
            'user_id' => Auth::id(),
            'name' => $this->newCategoryName,
            'icon' => $this->newCategoryIcon
        ]);

        $this->newCategoryName = '';
        $this->newCategoryIcon = '';
        $this->openCategoryModal = false;
    }

    // ==============================================
    // CREATE TYPE
    // ==============================================
    public function createType()
    {
        PersonalType::create([
            'user_id' => Auth::id(),
            'name' => $this->newTypeName,
            'color' => $this->newTypeColor
        ]);

        $this->newTypeName = '';
        $this->newTypeColor = '#0F1627';
        $this->openTypeModal = false;
    }

    // ==============================================
    // CREATE PERSONAL TASK
    // ==============================================
    public function createPersonal()
    {
        $due = $this->new_due_date_date.' '.$this->new_due_date_time;

        PersonalAssignment::create([
            'user_id' => Auth::id(),
            'title' => $this->new_title,
            'description' => $this->new_description,
            'category_id' => $this->new_category_id,
            'type_id' => $this->new_type_id,
            'priority_id' => $this->new_priority_id,
            'status_id' => 1,
            'due_date' => $due
        ]);

        $this->reset([
            'openCreateModal',
            'new_title',
            'new_description',
            'new_category_id',
            'new_type_id',
            'new_priority_id',
            'new_due_date_date',
            'new_due_date_time'
        ]);
    }

    public function render()
    {
        return view('livewire.assignment.combined-tasks', [
            'assignments' => $this->combined(),
            'personalCategories' => PersonalCategory::where('user_id',Auth::id())->get(),
            'personalTypes' => PersonalType::where('user_id',Auth::id())->get(),
            'priorities' => Priority::all(),
            'statuses' => Status::all()
        ]);
    }
}

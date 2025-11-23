<?php

namespace App\Livewire\Group;

use App\Models\Group;
use App\Models\GroupAssignments;
use App\Models\GroupTypeAssignment;
use App\Models\GroupAssignmentUser;
use App\Models\GroupUser;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $group_id;
    public $search = '';
    public $detailOpen = false;
    public $detailData;
    public $openStatusFor = null;

    // Edit properties
    public $editOpen = false;
    public $editId = null;
    public $editTitle = '';
    public $editDescription = '';
    public $editCategory = '';
    public $editPriority = '';
    public $editType = '';
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

    // FILTER & SORT
    public $filterPriority = '';
    public $filterType = '';
    public $filterStatus = ''; // '' = all, 'ongoing' = ongoing, 'finished' = finished
    public $sortStatus = '';
    public $sortDeadline = '';

    public function mount($group_id)
    {
        $this->group_id = $group_id;
        $this->loadIcons();
    }

    public function loadIcons()
    {
        $this->icons = array_values(array_diff(scandir(public_path('img/categoryIcons')), ['.', '..']));
    }

    public function isAdmin()
    {
        return GroupUser::where('group_id', $this->group_id)
            ->where('user_id', Auth::id())
            ->where('isAdmin', true)
            ->exists();
    }

    public function openDetail($id)
    {
        $this->detailData = GroupAssignments::with(['category', 'priority', 'typeAssignment'])->find($id);
        $this->detailOpen = true;
    }

    public function closeDetail()
    {
        $this->detailOpen = false;
    }

    public function openStatusDropdown($assignmentId)
    {
        $this->openStatusFor = $assignmentId;
    }

    public function closeStatusDropdown()
    {
        $this->openStatusFor = null;
    }

    public function updateStatus($assignmentId, $statusId)
    {
        GroupAssignmentUser::where('group_assignment_id', $assignmentId)
            ->where('user_id', auth()->id())
            ->update(['status_id' => $statusId]);

        $this->openStatusFor = null;
        $this->dispatch('statusUpdated');
    }

    public function openEdit($id)
    {
        if (!$this->isAdmin()) {
            session()->flash('error', 'Hanya admin yang dapat mengedit tugas grup');
            return;
        }

        $assignment = GroupAssignments::find($id);
        if ($assignment && $assignment->group_id == $this->group_id) {
            $this->editId = $id;
            $this->editTitle = $assignment->title;
            $this->editDescription = $assignment->description;
            $this->editCategory = $assignment->group_category_id;
            $this->editPriority = $assignment->priority_id;
            $this->editType = $assignment->group_type_assignment_id;
            $dueDate = \Carbon\Carbon::parse($assignment->due_date);
            $this->editDueDate = $dueDate->format('Y-m-d');
            $this->editDueTime = $dueDate->format('H:i');
            $this->editOpen = true;
        }
    }

    public function closeEdit()
    {
        $this->editOpen = false;
        $this->reset(['editId', 'editTitle', 'editDescription', 'editCategory', 'editPriority', 'editType', 'editDueDate', 'editDueTime', 'showCategoryModal', 'showTypeModal', 'newCategoryName', 'newCategoryIcon', 'newTypeName', 'newTypeColor']);
    }

    public function saveEdit()
    {
        if (!$this->isAdmin()) {
            session()->flash('error', 'Hanya admin yang dapat mengedit tugas grup');
            return;
        }

        $this->validate([
            'editTitle' => 'required|string|max:100',
            'editDescription' => 'required|string',
            'editCategory' => 'required|integer',
            'editPriority' => 'required|integer',
            'editType' => 'required|integer',
            'editDueDate' => 'required|date',
            'editDueTime' => 'required',
        ]);

        $datetime = $this->editDueDate . ' ' . $this->editDueTime;

        GroupAssignments::where('id', $this->editId)
            ->where('group_id', $this->group_id)
            ->update([
                'title' => $this->editTitle,
                'description' => $this->editDescription,
                'group_category_id' => $this->editCategory,
                'priority_id' => $this->editPriority,
                'group_type_assignment_id' => $this->editType,
                'due_date' => $datetime,
            ]);

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

        $category = \App\Models\GroupCategory::create([
            'name' => $this->newCategoryName,
            'group_id' => $this->group_id,
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

        $type = GroupTypeAssignment::create([
            'category' => $this->newTypeName,
            'color' => $this->newTypeColor,
            'group_id' => $this->group_id,
        ]);

        $this->editType = $type->id;
        $this->closeTypeModal();
    }

    public function render()
    {
        // ✔ Ambil list ID berdasarkan sort status
        $sortedIds = [];
        if ($this->sortStatus) {
            $sortedIds = GroupAssignmentUser::where('user_id', auth()->id())
                ->where('status_id', $this->sortStatus)
                ->pluck('group_assignment_id')
                ->toArray();
        }

        $assignments = GroupAssignments::with([
            'category',
            'priority',
            'typeAssignment',
            'users' => fn($q) => $q->select('users.id', 'users.name')
        ])
            ->where('group_id', $this->group_id)

            // ✔ SEARCH
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhereHas('category', fn($cq) =>
                        $cq->where('name', 'like', '%' . $this->search . '%')
                        )
                        ->orWhereHas('typeAssignment', fn($tq) =>
                        $tq->where('category', 'like', '%' . $this->search . '%')
                        );
                });
            })

            // ✔ FILTER PRIORITAS
            ->when($this->filterPriority, fn($q) =>
            $q->where('priority_id', $this->filterPriority)
            )

            // ✔ FILTER TIPE TUGAS (pakai nama kolom yang benar)
            ->when($this->filterType, fn($q) =>
            $q->where('group_type_assignment_id', $this->filterType)
            )

            // ✔ SORT STATUS — memakai FIELD TANPA subquery!
            ->when($this->sortStatus, function ($query) {

                if ($this->sortStatus == 2) {
                    // khusus untuk "sedang dikerjakan"
                    $query->leftJoin('group_assignment_users as gau', function ($join) {
                        $join->on('gau.group_assignment_id', '=', 'group_assignments.id')
                            ->where('gau.user_id', auth()->id());
                    })
                        ->select('group_assignments.*')
                        ->orderByRaw("FIELD(gau.status_id, 2, 1, 3)");
                }

                else {
                    // selain itu: gunakan normal ASC / DESC
                    $query->leftJoin('group_assignment_users as gau', function ($join) {
                        $join->on('gau.group_assignment_id', '=', 'group_assignments.id')
                            ->where('gau.user_id', auth()->id());
                    })
                        ->select('group_assignments.*')
                        ->orderBy('gau.status_id', $this->sortStatus == 1 ? 'asc' : 'desc');
                }

            })



            // ✔ SORT DEADLINE (asc/desc)
            ->when($this->sortDeadline, fn($q) =>
            $q->orderBy('due_date', $this->sortDeadline)
            )

            ->get();

        // Filter by status (based on current user's status for this group)
        if ($this->filterStatus === 'ongoing') {
            $assignments = $assignments->filter(function($assignment) {
                $userStatus = GroupAssignmentUser::where('group_assignment_id', $assignment->id)
                    ->where('user_id', auth()->id())
                    ->first();
                return $userStatus && in_array($userStatus->status_id, [1, 2]);
            });
        } elseif ($this->filterStatus === 'finished') {
            $assignments = $assignments->filter(function($assignment) {
                $userStatus = GroupAssignmentUser::where('group_assignment_id', $assignment->id)
                    ->where('user_id', auth()->id())
                    ->first();
                return $userStatus && $userStatus->status_id === 3;
            });
        }

        return view('livewire.group.index', [
            'assignments' => $assignments,
            'group' => Group::find($this->group_id),
            'categories' => \App\Models\GroupCategory::where('group_id', $this->group_id)->get(),
            'types' => GroupTypeAssignment::where('group_id', $this->group_id)->get(),
            'isAdmin' => $this->isAdmin(),
            'icons' => array_values(array_diff(scandir(public_path('img/categoryIcons')), ['.', '..'])),
        ]);
    }
}

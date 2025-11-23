<?php

namespace App\Livewire\Group;

use App\Models\Group;
use App\Models\GroupAssignments;
use App\Models\GroupTypeAssignment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Livewire\Component;
use App\Models\GroupCategory;

class Create extends Component
{
    public $group_id, $title, $group_category_id, $description, $group_type_assignment_id, $due_date;
    public $priority_id = 1;
    public $due_date_date;
    public $due_date_time;
    public $types = [];
    public $showCategoryModal = false;
    public $newCategoryName;
    public $newCategoryIcon;
    public $showTypeModal = false;
    public $newTypeName;
    public $newTypeColor = 128;
    public $categories = [];
    public $selectedCategoryName;
    public $selectedTypeName;
    public $selectedTypeColor;
    public $selectedTypeId;
    public $selectedCategoryIcon;



    protected $listeners = [
        'open-create-modal' => 'openModal',
        'type-added' => 'loadTypes',
    ];
    protected $listener = ['category-created' => 'refreshCategories'];
    public $isOpen = false;

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function mount(Group $group)
    {
        $this->group_id = $group->id;
        $this->categories = \App\Models\GroupCategory::where('group_id', $this->group_id)->get();
        $this->loadTypes();
        $this->types = GroupTypeAssignment::where('group_id', $this->group_id)->get();
    }


    public function openCategoryModal()
    {
        $this->showCategoryModal = true;
    }

    public function closeCategoryModal()
    {
        $this->showCategoryModal = false;
    }

    public function openTypeModal()
    {
        $this->showTypeModal = true;
    }

    public function closeTypeModal()
    {
        $this->showTypeModal = false;
        $this->newTypeName = '';
    }

    public function loadCategories()
    {
        $this->categories = GroupCategory::where('group_id', $this->group_id)->get();
    }

    public function loadTypes()
    {
        $this->types = GroupTypeAssignment::where('group_id', $this->group_id)->get();
    }

    public function selectCategory($id)
    {
        $this->group_category_id = $id;
        $category = $this->categories->firstWhere('id', $id);
        $this->selectedCategoryName = $category?->name;
        $this->selectedCategoryIcon = $category->path;
    }

    public function selectType($id)
    {
        $type = GroupTypeAssignment::find($id);
        $this->selectedTypeId = $type->id;
        $this->group_type_assignment_id = $type->id;
        $this->selectedTypeColor = $type->color;
        $this->selectedTypeName = $type->category;
    }

    public function saveType()
    {
        $this->validate([
            'newTypeName' => 'required|string|max:50|unique:group_type_assignments,category',
            'newTypeColor' => 'required|integer|min:0|max:255',
        ]);

        $type = GroupTypeAssignment::create([
            'group_id' => $this->group_id,
            'category' => $this->newTypeName,
            'color' => $this->newTypeColor,
        ]);

        $this->closeTypeModal();

        $this->loadTypes(); // auto refresh
        $this->selectType($type->id); // auto pilih tipe yang baru dibuat
        $this->types = \App\Models\GroupTypeAssignment::where('group_id', $this->group_id)->get();
    }

    public function saveCategory()
    {
        $this->validate([
            'newCategoryName' => [
                'required',
                'min:2',
                Rule::unique('group_categories', 'name')
                    ->where('group_id', $this->group_id)
            ],
            'newCategoryIcon' => 'required',
        ]);
        GroupCategory::create([
            'group_id' => $this->group_id,
            'name' => $this->newCategoryName,
            'path' => $this->newCategoryIcon,
        ]);
        $this->closeCategoryModal();
        $this->categories = \App\Models\GroupCategory::where('group_id', $this->group_id)->get();
    }

    public function refreshCategories()
    {
        $this->categories = \App\Models\GroupCategory::where('group_id', $this->group_id)->get();
    }

    public function getIconsProperty()
    {
        return [
            'emojione-monotone_flag-for-indonesia.png',
            'hugeicons_ai-security-01.png',
            'icon-park-outline_chinese.png',
            'icon-park-solid_english.png',
            'mage_photoshop.png',
            'maki_religious-christian.png',
            'map_political.png',
            'mynaui_math.png',
            'nimbus_marketing.png',
            'ph_basketball-fill.png',
            'ph_network-fill.png',
            'streamline-plump_web-solid.png',
        ];
    }

    public function render()
    {
        $categories = \App\Models\GroupCategory::where('group_id', $this->group_id)->get();
        return view('livewire.group.create', ['types' => $this->types, 'group_id' => $this->group_id, 'categories' => $categories]);
    }

    public function store()
    {
        $validatedRequest = $this->validate([
           'group_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'min:1', 'max:10'],
            'group_category_id' => ['required', 'integer'],
            'description' => ['required', 'string', 'min:1', 'max:500'],
            'priority_id' => ['required', 'integer', 'between:1,3'],
            'group_type_assignment_id' => ['required', 'integer'],
            'due_date_date' => ['required', 'date', 'after_or_equal:today'],
            'due_date_time' => ['required', 'date_format:H:i'],
        ]);
        $validatedRequest['due_date'] = $this->due_date_date . ' ' . $this->due_date_time . ':00';

        $assignment = GroupAssignments::create($validatedRequest);
        $group = Group::find($this->group_id);
        foreach ($group->users as $user) {
            \App\Models\GroupAssignmentUser::create([
                'group_assignment_id' => $assignment->id,
                'user_id' => $user->id,
                'status_id' => 1,
            ]);
        }


        return redirect()->route('group.show', $this->group_id);
    }
}

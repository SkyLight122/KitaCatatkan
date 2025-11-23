<?php

namespace App\Livewire\Assignments;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;
use App\Models\Category;
use App\Models\TypeAssignment;
use App\Models\Priority;
use Illuminate\Validation\Rule;

class Create extends Component
{
    public $isOpen = false;

    public $title = '';
    public $description = '';
    public $category_id = '';
    public $priority_id = 1;
    public $type_assignment_id = '';
    public $due_date_date = '';
    public $due_date_time = '';

    // Category modal states
    public $showCategoryModal = false;
    public $newCategoryName = '';
    public $newCategoryIcon = '';
    public $selectedCategoryName = '';
    public $selectedCategoryIcon = '';

    // Type modal states
    public $showTypeModal = false;
    public $newTypeName = '';
    public $newTypeColor = 128; // Default to middle gray (0-255)
    public $selectedTypeName = '';
    public $selectedTypeColor = null;
    public $selectedTypeId = '';

    public $categories = [];
    public $types = [];

    public function mount()
    {
        $this->loadCategories();
        $this->loadTypes();
    }

    #[On('open-create-modal')]
    public function openModal()
    {
        $this->resetValidation();

        $this->reset([
            'title',
            'description',
            'category_id',
            'priority_id',
            'type_assignment_id',
            'due_date_date',
            'due_date_time',
            'selectedCategoryName',
            'selectedCategoryIcon',
            'selectedTypeName',
            'selectedTypeColor',
            'selectedTypeId',
        ]);

        $this->priority_id = 1;
        $this->loadCategories();
        $this->loadTypes();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function loadCategories()
    {
        $this->categories = Category::where('user_id', Auth::id())->get();
    }

    public function loadTypes()
    {
        $this->types = TypeAssignment::where('user_id', Auth::id())->get();
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

    public function openTypeModal()
    {
        $this->showTypeModal = true;
    }

    public function closeTypeModal()
    {
        $this->showTypeModal = false;
        $this->newTypeName = '';
        $this->newTypeColor = 128;
    }

    public function selectCategory($id)
    {
        $this->category_id = $id;
        $category = $this->categories->firstWhere('id', $id);
        $this->selectedCategoryName = $category?->name ?? '';
        $this->selectedCategoryIcon = $category?->path ?? '';
    }

    public function selectType($id)
    {
        $type = TypeAssignment::find($id);
        if ($type) {
            $this->type_assignment_id = $type->id;
            $this->selectedTypeId = $type->id;
            $this->selectedTypeName = $type->category;
            // Convert integer color to RGB grayscale format
            $colorValue = $type->color;
            $this->selectedTypeColor = "rgb($colorValue, $colorValue, $colorValue)";
        }
    }

    public function saveCategory()
    {
        $this->validate([
            'newCategoryName' => [
                'required',
                'min:2',
                Rule::unique('categories', 'name')
                    ->where('user_id', Auth::id())
            ],
            'newCategoryIcon' => 'required',
        ]);

        $category = Category::create([
            'user_id' => Auth::id(),
            'name' => $this->newCategoryName,
            'path' => $this->newCategoryIcon,
        ]);

        $this->closeCategoryModal();
        $this->loadCategories();
        $this->selectCategory($category->id);
    }

    public function saveType()
    {
        $this->validate([
            'newTypeName' => [
                'required',
                'string',
                'max:50',
                Rule::unique('type_assignments', 'category')
                    ->where('user_id', Auth::id())
            ],
            'newTypeColor' => 'required|string',
        ]);

        // Convert hex color to integer if needed
        $colorValue = is_numeric($this->newTypeColor) 
            ? $this->newTypeColor 
            : intval(substr($this->newTypeColor, 1), 16) % 256;

        $type = TypeAssignment::create([
            'user_id' => Auth::id(),
            'category' => $this->newTypeName,
            'color' => $colorValue,
        ]);

        $this->closeTypeModal();
        $this->loadTypes();
        $this->selectType($type->id);
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

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:10',
            'description' => 'required|string',
            'category_id' => 'required|integer',
            'priority_id' => 'required|integer',
            'type_assignment_id' => 'required|integer',
            'due_date_date' => 'required|date',
            'due_date_time' => 'required',
        ]);

        $datetime = $this->due_date_date . ' ' . $this->due_date_time;

        Assignment::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'priority_id' => $this->priority_id,
            'type_assignment_id' => $this->type_assignment_id,
            'due_date' => $datetime,
            'status_id' => 1,
        ]);

        session()->flash('success', 'Berhasil menambahkan tugas pribadi!');

        $this->isOpen = false;

        $this->dispatch('task-added');
    }

    public function render()
    {
        return view('livewire.assignments.create', [
            'categories' => $this->categories,
            'types' => $this->types,
            'priorities' => Priority::all(),
            'icons' => $this->icons,
        ]);
    }
}

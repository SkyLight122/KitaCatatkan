<?php

namespace App\Livewire\Group;

use App\Models\Group;
use Livewire\Component;

class GroupMember extends Component
{
    public $group;
    public $searchMember = '';

    public function mount($group)
    {
        $this->group = $group;
    }

    public function render()
    {
        $members = $this->group->users()
            ->when($this->searchMember, function($q){
                $q->where('name', 'like', '%' . $this->searchMember . '%');
            })
            ->get();

        return view('livewire.group.group-member', [
            'members' => $members
        ]);
    }
}

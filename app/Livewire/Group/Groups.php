<?php

namespace App\Livewire\Group;

use App\Models\Group;
use App\Models\GroupList;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Groups extends Component
{
    public Group $group;

    public function mount(Group $group)
    {
        $this->group = $group;
    }

    public function isAdmin()
    {
        return GroupUser::where('group_id', $this->group->id)
            ->where('user_id', Auth::id())
            ->where('isAdmin', true)
            ->exists();
    }

    public function render()
    {
        return view('livewire.group.groups', [
            'group' => $this->group,
            'isAdmin' => $this->isAdmin()
        ])->layout('components.layouts.GroupLayout');
    }
}

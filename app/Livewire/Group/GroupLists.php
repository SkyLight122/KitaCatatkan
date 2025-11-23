<?php

namespace App\Livewire\Group;

use App\Models\Group;
use App\Models\GroupList;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GroupLists extends Component
{

    public function render()
    {
        $groups = Auth::user()->groups;
        return view('livewire.Group.group-lists', compact('groups'));
    }


}

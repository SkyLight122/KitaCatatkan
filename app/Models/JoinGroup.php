<?php

namespace App\Livewire\Group;

use Livewire\Component;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class JoinGroup extends Component
{
    public $show = false;
    public $key = '';

    public function getListeners()
    {
        return [
            'open-join-group' => 'openModal',
        ];
    }

    public function openModal() { $this->show = true; }

    public function closeModal() { $this->show = false; }

    public function join()
    {
        $group = Group::where('key', $this->key)->first();

        if (!$group) {
            session()->flash('error', 'Kode grup tidak ditemukan.');
            return;
        }

        $group->users()->syncWithoutDetaching([Auth::id()]);

        session()->flash('success', 'Berhasil bergabung ke grup!');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.Group.joingroup');
    }
}

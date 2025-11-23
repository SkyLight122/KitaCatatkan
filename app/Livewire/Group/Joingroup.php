<?php

namespace App\Livewire\Group;

use Livewire\Component;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class Joingroup extends Component
{
    public $show = false;
    public $key;
    #[Layout('layouts.app')]

    public function getListeners()
    {
        return [
            'open-join-group' => 'openModal'
        ];
    }

    public function openModal()
    {
        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false;
    }

    public function join()
    {
        $this->validate([
            'key' => 'required'
        ], [
            'key.required' => 'Kode grup wajib diisi.'
        ]);

        $group = Group::where('key', $this->key)->first();

        if (!$group) {
            session()->flash('error', 'Kode grup tidak ditemukan.');
            return;
        }

        // Cek apakah user sudah bergabung
        if (Auth::user()->groups()->where('group_id', $group->id)->exists()) {
            session()->flash('error', 'Anda sudah bergabung dengan grup ini.');
            return;
        }

        // Add user
        $group->users()->syncWithoutDetaching([Auth::id()]);

        // Redirection to group page
        session()->flash('success', 'Berhasil bergabung! Mengalihkan...');
        
        return redirect()->route('groups.show', $group->id);
    }



    public function render()
    {
        return view('livewire.group.joingroup');
    }
}

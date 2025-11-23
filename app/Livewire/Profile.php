<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    use WithFileUploads;

    public $username;
    public $email;
    public $instance_name;
    public $photo;
    public $photo_preview;

    public $editing = false;
    public $show = false;
    public $user;

    public function getListeners()
    {
        return [
            'open-profile' => 'openModal',
        ];
    }

    public function openModal()
    {
        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false;
        $this->editing = false;
    }

    public function mount()
    {
        $this->user = Auth::user();

        $this->username = $this->user->name;
        $this->email = $this->user->email;
        $this->instance_name = $this->user->instanceCollab?->name ?? '-';

        // Path file real
        $realPath = public_path($this->user->path);

        if ($this->user->path && file_exists($realPath)) {
            // Path url
            $this->photo_preview = asset($this->user->path);
        } else {
            $this->photo_preview = asset('images/profile.png');
        }
    }


    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:2048'
        ]);

        $this->photo_preview = $this->photo->temporaryUrl();
    }

    public function enableEdit()
    {
        $this->editing = true;
    }

    public function save()
    {
        $user = Auth::user();

        if ($this->photo) {

            // Buat nama unik
            $filename = md5($user->id . time()) . '.' . $this->photo->getClientOriginalExtension();

            // Simpan ke storage/app/public/profile
            $this->photo->storeAs('profile', $filename, 'public');

            // Simpan path URL-ready
            $user->path = 'storage/profile/' . $filename;
        }

        $user->name = $this->username;
        $user->save();

        $this->editing = false;
        session()->flash('success', 'Profil berhasil diperbarui!');
    }


    public function render()
    {
        return view('livewire.profile');
    }
}

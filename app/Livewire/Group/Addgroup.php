<?php

namespace App\Livewire\Group;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Auth;

class Addgroup extends Component
{
    use WithFileUploads;

    public $title;
    public $description;
    public $photo_profile;
    public $photo_preview;

    public $show = false;

    public function getListeners()
    {
        return [
            'open-add-group' => 'openModal',
        ];
    }

    public function openModal()
    {
        $this->resetFields();
        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false;
    }

    private function resetFields()
    {
        $this->title = '';
        $this->description = '';
        $this->photo_profile = null;
        $this->photo_preview = null;
    }

    private function generateUniqueKey()
    {
        do {
            $key = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (Group::where('key', $key)->exists());
        return $key;
    }

    public function updatedPhotoProfile()
    {
        $this->validate([
            'photo_profile' => 'image|max:2048'
        ]);
        $this->photo_preview = $this->photo_profile->temporaryUrl();
    }

    public function createGroup()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);
        $key = $this->generateUniqueKey();

        $photoPath = null;
        if ($this->photo_profile) {
            $filename = md5(time()) . '.' . $this->photo_profile->getClientOriginalExtension();
            $photoPath = 'storage/' . $this->photo_profile->storeAs('group_profile', $filename, 'public');
        }
        $group = Group::create([
            'title'        => $this->title,
            'description'  => $this->description,
            'photo_profile'=> $photoPath,
            'key'          => $key,
        ]);
        GroupUser::create([
            'user_id'  => Auth::id(),
            'group_id' => $group->id,
            'isAdmin'  => true,
        ]);
        session()->flash('success', 'Group berhasil dibuat!');
        return redirect()->route('group.show', $group->id);
    }

    public function render()
    {
        return view('livewire.group.addgroup');
    }
}

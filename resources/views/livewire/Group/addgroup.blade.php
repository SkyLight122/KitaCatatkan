<div class="w-full">
    <button
        wire:click="openModal"
        class="w-full px-3 py-2 bg-[#0F1627] hover:bg-[#0F1627]/75 text-white text-sm rounded-lg font-medium"
    >
        Add
    </button>

    @if($show)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-8 w-[500px] relative">
                <button class="absolute right-4 top-4 text-xl" wire:click="closeModal">&times;</button>
                <h2 class="text-center text-2xl font-bold mb-6">Buat Group Baru</h2>
                <div class="mb-4">
                    <label class="font-semibold">Nama Group</label>
                    <input type="text" wire:model="title"
                           class="w-full mt-1 border rounded-lg px-3 py-2 @error('title') border-red-500 @enderror">

                    @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="font-semibold">Deskripsi</label>
                    <textarea wire:model="description"
                              class="w-full mt-1 border rounded-lg px-3 py-2 h-24 @error('description') border-red-500 @enderror"></textarea>

                    @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            
                <button wire:click="createGroup"
                        class="w-full bg-[#0F1627] hover:bg-[#0F1627]/75 text-white py-3 rounded-lg mt-4">
                    Buat Group
                </button>
            </div>
        </div>
    @endif
</div>

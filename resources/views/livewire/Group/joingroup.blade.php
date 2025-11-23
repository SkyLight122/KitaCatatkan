<div class="w-full">
    <button
        wire:click="openModal"
        class="w-full px-3 py-2 bg-[#0F1627] hover:bg-[#0F1627]/75 text-white text-sm rounded-lg font-medium"
    >
        Join
    </button>

    @if($show)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white p-6 w-[380px] rounded-xl relative shadow-xl">
                <button class="absolute right-3 top-2 text-xl"
                        wire:click="closeModal">&times;</button>
                <h2 class="text-center text-xl font-bold mb-4">Masukkan Kode Grup</h2>
                <input type="text" wire:model="key"
                       class="w-full border rounded-lg px-3 py-2 mt-1 @error('key') border-red-500 @enderror"
                       placeholder="Kode Grup">
                @error('key')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                @if(session()->has('error'))
                    <p class="text-red-600 text-sm mt-1">{{ session('error') }}</p>
                @endif
                <button wire:click="join"
                        class="w-full bg-[#0F1627] hover:bg-[#0F1627]/75 text-white py-2 rounded-lg mt-3">
                    Join
                </button>
            </div>
        </div>
    @endif
</div>

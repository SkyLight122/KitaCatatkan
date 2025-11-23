<div>
    @if($show)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

            <div class="bg-white rounded-2xl p-8 w-[480px] relative shadow-xl min-h-[620px] flex flex-col">

                <!-- Close -->
                <button class="absolute right-4 top-4 text-2xl"
                        wire:click="closeModal">&times;</button>

                <!-- FOTO PROFIL -->
                <div class="flex flex-col items-center mb-4 mt-4">
                    <label class="cursor-pointer relative block">

                        <img src="{{ $photo_preview }}"
                             class="w-28 h-28 rounded-full object-cover border shadow mx-auto">

                        @if($editing)
                            <input type="file" class="hidden" wire:model="photo">
                        @endif
                    </label>

                    <!-- AREA LABEL TETAP ADA, TAPI TRANSPARAN SAAT NON-EDIT -->
                    <p class="text-xs text-gray-500 mt-2 text-center h-4 transition-all duration-200
                           {{ $editing ? 'opacity-100' : 'opacity-0' }}">
                        Klik foto untuk mengubah
                    </p>
                </div>

                <h2 class="text-center text-2xl font-bold mb-6">Profil Anda</h2>

                <div class="flex-1">
                    <!-- USERNAME -->
                    <div class="mb-4">
                        <label class="font-semibold">Username</label>
                        <input type="text"
                               wire:model="username"
                               class="w-full mt-1 border rounded-lg px-3 py-2
                               {{ !$editing ? 'bg-gray-200 cursor-not-allowed' : '' }}"
                               @if(!$editing) disabled @endif>
                    </div>

                    <!-- EMAIL -->
                    <div class="mb-4">
                        <label class="font-semibold">Email</label>
                        <input type="text" value="{{ $email }}"
                               class="w-full mt-1 border rounded-lg px-3 py-2 bg-gray-200 cursor-not-allowed"
                               disabled>
                    </div>

                    <!-- INSTANSI -->
                    <div class="mb-4">
                        <label class="font-semibold">Instansi</label>
                        <input type="text" value="{{ $instance_name }}"
                               class="w-full mt-1 border rounded-lg px-3 py-2 bg-gray-200 cursor-not-allowed"
                               disabled>
                    </div>
                </div>

                <!-- TOMBOL -->
                <div class="mt-auto">
                    @if(!$editing)
                        <button wire:click="enableEdit"
                                class="w-full bg-[#0F1627] text-white py-3 rounded-lg mt-3">
                            Edit Profil
                        </button>
                    @else
                        <button wire:click="save"
                                class="w-full bg-green-600 text-white py-3 rounded-lg mt-3">
                            Simpan Perubahan
                        </button>
                    @endif
                </div>

            </div>
        </div>
    @endif
</div>

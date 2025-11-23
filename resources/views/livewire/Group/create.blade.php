<div>
    @if($isOpen)
        <div class="fixed inset-0 bg-black/50 z-40"></div>
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-xl w-[450px] max-h-[90vh] overflow-y-auto shadow-xl space-y-4">

            <div class="flex justify-between items-center mb-2">
                    <h2 class="text-lg font-semibold">Tambah Tugas</h2>
                    <button wire:click="closeModal" class="text-gray-500 hover:text-black">✖</button>
                </div>

                <form wire:submit.prevent="store" class="space-y-4">

                    <flux:input
                        icon="document-text"
                        wire:model="title"
                        :label="__('Judul')"
                        placeholder="Judul"
                    />
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    <label class="font-medium">Kategori</label>
                    <div class="relative" x-data="{ open: false }">
                        <button
                            type="button"
                            @click="open = !open"
                            class="w-full border px-4 pr-8 py-2 rounded-xl flex justify-between items-center bg-white hover:bg-gray-50"
                        >

                            <div class="flex items-center gap-2">
                                @if($selectedCategoryIcon)
                                    <img src="{{ asset('img/categoryIcons/' . $selectedCategoryIcon) }}" class="w-5 h-5">
                                @endif

                                {{ $selectedCategoryName ?? 'Pilih Kategori' }}
                            </div>


                            <svg class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 text-gray-600" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            x-show="open"
                            @click.outside="open = false"
                            class="absolute mt-1 w-full bg-white shadow-lg rounded-xl border z-50"
                        >
                            @foreach ($categories as $category)
                                <button
                                    type="button"
                                    wire:click="selectCategory({{ $category->id }})"
                                    @click="open = false"
                                    class="w-full px-4 py-2 flex items-center gap-2 hover:bg-gray-100 text-left"
                                >
                                    <img src="{{ asset('img/categoryIcons/' . $category->path) }}" class="w-5 h-5">
                                    {{ $category->name }}
                                </button>
                            @endforeach
                            <div class="border-t my-1"></div>
                            <button
                                type="button"
                                @click="open = false; $wire.openCategoryModal()"
                                class="w-full px-4 py-2 flex items-center gap-2 bg-[#0F1627] hover:bg-[#0F1627]/75 text-white"
                            >
                                + Tambah kategori
                            </button>
                        </div>
                    </div>
                    @if($showCategoryModal)
                        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

                            <div class="bg-white p-6 rounded-xl w-[350px] shadow-xl">
                                <div class="flex justify-between mb-3">
                                    <h2 class="text-lg font-semibold">Tambah Kategori</h2>
                                    <button type="button" wire:click="closeCategoryModal">✖</button>
                                </div>
                                <div class="space-y-4">
                                    <input
                                        type="text"
                                        wire:model="newCategoryName"
                                        class="w-full border px-3 py-2 rounded-lg"
                                        placeholder="Nama kategori"
                                    />
                                    <div>
                                        <p class="font-medium mb-2">Pilih Icon</p>
                                        <div class="grid grid-cols-5 gap-3">
                                            @foreach ($this->icons as $icon)
                                                <div
                                                    wire:click="$set('newCategoryIcon', '{{ $icon }}')"
                                                    class="p-2 border rounded-xl cursor-pointer
                            @if($newCategoryIcon === $icon) bg-blue-100 border-gray-600 @endif"
                                                >
                                                    <img src="{{ asset('img/categoryIcons/' . $icon) }}" class="w-7 h-7">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        wire:click="saveCategory"
                                        class="w-full px-3 py-2 bg-[#0F1627] hover:bg-[#0F1627]/75 text-white rounded-lg"
                                    >
                                        Simpan
                                    </button>
                                </div>
                            </div>

                        </div>
                    @endif

                    <div class="flex gap-1 w-full">
                        <div class="w-[69%]">
                            <flux:input
                                class="w-full"
                                label="Deadline (Tanggal)"
                                icon="calendar"
                                type="date"
                                wire:model="due_date_date"
                            />
                        </div>

                        <div class="w-[31%]">
                            <flux:input
                                class="w-full"
                                label="Waktu"
                                icon="clock"
                                type="time"
                                wire:model="due_date_time"
                                step="60"
                            />
                        </div>
                    </div>

                    <flux:select placeholder="Seberapa Penting Tugasmu?" :label="__('Prioritas')" wire:model="priority_id">
                        <flux:select.option value="1">Rendah</flux:select.option>
                        <flux:select.option value="2">Sedang</flux:select.option>
                        <flux:select.option value="3">Tinggi</flux:select.option>
                    </flux:select>
                    @error('priority_id') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    <label class="font-medium">Tipe Tugas</label>
                    <div class="relative" x-data="{ open: false }">
                        <button
                            type="button"
                            @click="open = !open"
                            class="w-full border px-4 pr-8 py-2 rounded-xl flex justify-between items-center bg-white hover:bg-gray-50"
                        >
                            <div class="flex items-center gap-2">
                                @if($selectedTypeColor !== null)
                                    <div class="w-4 h-4 rounded border"
                                         style="background-color: rgb({{ $selectedTypeColor }}, {{ $selectedTypeColor }}, {{ $selectedTypeColor }});">
                                    </div>
                                @endif

                                {{ $selectedTypeName ?? '-- Pilih Tipe --' }}
                            </div>


                            <svg class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 text-gray-600" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            x-show="open"
                            @click.outside="open = false"
                            class="absolute mt-1 w-full bg-white shadow-lg rounded-xl border z-50"
                        >
                            @foreach ($types as $type)
                                <button type="button"
                                        wire:click="selectType({{ $type->id }})"
                                        @click="open = false"
                                        class="w-full px-4 py-2 hover:bg-gray-100 text-left"
                                >
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded border"
                                             style="background-color: rgb({{ $type->color }}, {{ $type->color }}, {{ $type->color }});">
                                        </div>
                                        {{ $type->category }}
                                    </div>
                                </button>
                            @endforeach
                            <div class="border-t my-1"></div>
                            <button
                                type="button"
                                @click="open = false; $wire.openTypeModal()"
                                class="w-full px-4 py-2 bg-[#0F1627] hover:bg-[#0F1627]/75 text-white text-left"
                            >
                                + Tambah Tipe
                            </button>
                        </div>
                    </div>
                    @if($showTypeModal)
                        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

                            <div class="bg-white p-6 rounded-xl w-[350px] shadow-xl">

                                <div class="flex justify-between mb-3">
                                    <h2 class="text-lg font-semibold">Tambah Tipe Tugas</h2>
                                    <button type="button" wire:click="closeTypeModal">✖</button>
                                </div>

                                <div class="space-y-4">

                                    <input
                                        type="text"
                                        wire:model="newTypeName"
                                        class="w-full border px-3 py-2 rounded-lg"
                                        placeholder="Nama tipe tugas"
                                    />
                                    @error('newTypeName') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror

                                    <label class="font-medium text-sm">Warna (grayscale)</label>
                                    <input
                                        x-ref="rangeInput"
                                        type="range"
                                        min="0"
                                        max="255"
                                        wire:model="newTypeColor"
                                        class="grayscale-range w-full cursor-pointer"
                                    >
                                    @error('newTypeColor') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror

                                    <button
                                        type="button"
                                        wire:click="saveType"
                                        class="w-full px-3 py-2 bg-[#0F1627] hover:bg-[#0F1627]/75 text-white rounded-lg"
                                    >
                                        Simpan
                                    </button>

                                </div>
                            </div>

                        </div>
                    @endif

                    @error('group_type_assignment_id') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    <flux:textarea
                        wire:model="description"
                        :label="__('Deskripsi')"
                        placeholder="Masukkan Deskripsi Tugas"
                    />
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    <flux:button
                        class="w-full mt-2 bg-[#0F1627] hover:bg-[#0F1627]/75 text-white"
                        type="submit"
                    >
                        Tambah Tugas
                    </flux:button>

                </form>

            </div>
        </div>
    @endif
</div>

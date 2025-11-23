@php
    use App\Models\GroupAssignmentUser;use Carbon\Carbon;
@endphp

<div>
    <div class="w-100">
        <flux:input
            class="mb-4"
            icon="magnifying-glass"
            placeholder="Cari Tugas"
            wire:model.live="search"
        />
    </div>
    <div class="flex items-center gap-4 mb-4">

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="text-sm font-semibold flex items-center gap-1">
                + Filter
            </button>
            <div x-show="open" @click.outside="open = false"
                 class="absolute z-50 bg-white shadow-lg rounded-lg border p-4 w-48 mt-2">
                <div class="mb-3">
                    <p class="text-xs font-semibold mb-1">Prioritas</p>
                    <select class="w-full border rounded px-2 py-1 text-sm"
                            wire:model.live="filterPriority">
                        <option value="">Semua</option>
                        <option value="1">Rendah</option>
                        <option value="2">Sedang</option>
                        <option value="3">Tinggi</option>
                    </select>
                </div>
                <div>
                    <p class="text-xs font-semibold mb-1">Tipe Tugas</p>
                    <select class="w-full border rounded px-2 py-1 text-sm"
                            wire:model.live="filterType">
                        <option value="">Semua</option>
                        @foreach($types as $t)
                            <option value="{{ $t->id }}">{{ $t->category }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- STATUS FILTER --}}
                <div>
                    <p class="text-xs font-semibold mb-1">Status</p>
                    <select class="w-full border rounded px-2 py-1 text-sm"
                            wire:model.live="filterStatus">
                        <option value="">Semua</option>
                        <option value="ongoing">On Going</option>
                        <option value="finished">Finished</option>
                    </select>
                </div>
            </div>
        </div>
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="text-sm font-semibold flex items-center gap-1">
                + Sort
            </button>
            <div x-show="open" @click.outside="open = false"
                 class="absolute z-50 bg-white shadow-lg rounded-lg border p-4 w-48 mt-2">
                {{-- Sort Status --}}
                <div class="mb-3">
                    <p class="text-xs font-semibold mb-1">Status</p>
                    <select class="w-full border rounded px-2 py-1 text-sm"
                            wire:model.live="sortStatus">
                        <option value="">Default</option>
                        <option value="1">Belum Dimulai</option>
                        <option value="2">Sedang Dikerjakan</option>
                        <option value="3">Selesai</option>
                    </select>
                </div>
                <div>
                    <p class="text-xs font-semibold mb-1">Deadline</p>
                    <select class="w-full border rounded px-2 py-1 text-sm"
                            wire:model.live="sortDeadline">
                        <option value="">Default</option>
                        <option value="asc">Terdekat</option>
                        <option value="desc">Terjauh</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full p-4" x-data>
        <div class="grid grid-cols-7 font-semibold text-gray-600 px-4 pb-2">
            <div>Status</div>
            <div>Kategori</div>
            <div>Deadline</div>
            <div>Judul</div>
            <div>Prioritas</div>
            <div>Tipe Tugas</div>
            <div>Data (anggota yang selesai)</div>
        </div>
        @foreach($assignments as $assignment)
            @php
                $pivot = GroupAssignmentUser::where('group_assignment_id', $assignment->id)
                ->where('user_id', Auth::id())
                ->first();

                $statusId = $pivot?->status_id ?? 1;

                // ⬅ pindahkan ke sini
                $isLate = \Carbon\Carbon::parse($assignment->due_date)->isPast() && $statusId !== 3;

                $statusNames = [
                    1 => 'Belum dimulai',
                    2 => 'sedang dikerjakan',
                    3 => 'Selesai'
                ];

                $statusColors = [
                    1 => 'bg-[#E3E5EA] text-black',
                    2 => 'bg-[#0F1627] text-white',
                    3 => 'bg-white text-black border border-gray-600',
                ];

                $statusName = $statusNames[$statusId];

                $priorityColors = [
                    1 => 'bg-red-700 text-white',
                    2 => 'bg-blue-600 text-white',
                    3 => 'bg-gray-700 text-white',
                ];

                $total = $assignment->group->users->count();
                $done = $assignment->users()->wherePivot('status_id', 3)->count();
                $percent = intval(($done / max($total, 1)) * 100);
            @endphp
            <div
                class="relative bg-white shadow-sm rounded-xl p-4 mb-3 grid grid-cols-7 items-center gap-4 hover:cursor-pointer
                    {{ $isLate ? 'bg-red-50 border border-red-300' : '' }}"
                wire:click="openDetail({{ $assignment->id }})"
            >
                <div class="relative select-none"
                     wire:click.stop="openStatusDropdown({{ $assignment->id }})">

                <span
                    class="px-3 py-1 text-xs font-semibold rounded-full inline-flex items-center gap-2 cursor-pointer {{ $statusColors[$statusId] }}">
                    ● {{ $statusName }}
                </span>
                    @if($openStatusFor === $assignment->id)
                        <div class="absolute mt-2 left-0 bg-white rounded-xl shadow-xl w-48 p-3 z-50 border"
                             x-data
                             @click.outside="$wire.closeStatusDropdown()">

                            {{-- TERPILIH --}}
                            <div class="text-xs text-gray-600 mb-2">Terpilih</div>

                            <div
                                class="px-3 py-2 rounded-lg text-sm mb-3 flex items-center gap-2 {{ $statusColors[$statusId] }}">
                                ● {{ $statusName }}
                            </div>

                            {{-- PILIHAN LAIN --}}
                            <div class="text-xs text-gray-600 mb-2">Pilihan lain</div>

                            @foreach([1,2,3] as $id)
                                @if($id !== $statusId)
                                    <div
                                        wire:click="updateStatus({{ $assignment->id }}, {{ $id }})"
                                        class="px-3 py-2 rounded-lg text-sm cursor-pointer flex items-center gap-2 hover:bg-gray-100"
                                    >
                                        ● {{ $statusNames[$id] }}
                                    </div>
                                @endif
                            @endforeach

                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    @if($assignment->category?->path)
                        <img src="{{ asset('img/categoryIcons/' . $assignment->category->path) }}" class="w-6 h-6">
                    @endif
                    <span class="font-semibold">{{ $assignment->category?->name ?? '-' }}</span>
                </div>
                <div class="font-semibold">
                    {{ \Carbon\Carbon::parse($assignment->due_date)->translatedFormat('j F Y') }}
                </div>
                <div class="font-semibold">{{ $assignment->title }}</div>
                <div>
                <span
                    class="px-3 py-1 text-xs font-semibold rounded-full {{ $priorityColors[$assignment->priority_id] }}">
                    {{ $assignment->priority?->name }}
                </span>
                </div>
                <div>
                @php
                    $typeColor = $assignment->typeAssignment?->color ?? 100;
                    $typeTextColor = $typeColor >= 178 ? '#000000' : '#FFFFFF';
                @endphp
                <span
                    class="px-3 py-1 text-xs font-semibold rounded-full inline-flex items-center gap-2"
                    style="background-color: rgb({{ $typeColor }}, {{ $typeColor }}, {{ $typeColor }}) !important; color: {{ $typeTextColor }} !important;"
                >
                    ● {{ $assignment->typeAssignment?->category }}
                </span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold">{{ $percent }}%</span>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-black rounded-full" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            </div>
        @endforeach
        @if($detailOpen)
            <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
                <div class="bg-white w-[600px] max-w-[90%] rounded-2xl shadow-xl p-8 relative">
                    <div class="flex justify-between items-start mb-6">
                        <h2 class="text-2xl font-bold">
                            {{ $detailData->title }}
                        </h2>
                        <div class="flex gap-2">
                            @if($isAdmin)
                                <button wire:click="openEdit({{ $detailData->id }})"
                                        class="px-3 py-2 bg-[#0F1627] text-white rounded-lg hover:bg-gray-800 text-sm">
                                    ✎ Edit
                                </button>
                            @endif
                            <button
                                class="text-2xl text-gray-600 hover:text-black hover:cursor-pointer"
                                wire:click="closeDetail"
                            >
                                &times;
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="space-y-2">
                            <p class="text-sm text-gray-500">Deadline</p>
                            <p class="text-lg font-semibold">
                                {{ \Carbon\Carbon::parse($detailData->due_date)->translatedFormat('j F Y') }}
                            </p>
                            <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($detailData->due_date)->format('H:i') }}</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm text-gray-500">Prioritas</p>
                            <p class="font-semibold">{{ $detailData->priority?->name ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Kategori</p>
                            <p class="font-semibold">{{ $detailData->category?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tipe Tugas</p>
                            <p class="font-semibold">{{ $detailData->typeAssignment?->category ?? '-' }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-2">Deskripsi</p>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line p-4 bg-gray-50 rounded-lg">
                            {{ $detailData->description }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- EDIT MODAL --}}
        @if($editOpen)
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-2xl shadow-xl w-[700px] relative">
                    <button wire:click="closeEdit" class="absolute top-4 right-4 text-gray-600 hover:text-black text-xl">×</button>
                    <h3 class="text-xl font-bold mb-6">Edit Tugas Grup</h3>

                    <form wire:submit.prevent="saveEdit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Judul</label>
                            <input type="text" wire:model="editTitle" class="w-full border rounded-lg px-3 py-2" required>
                            @error('editTitle') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium mb-1">Kategori</label>
                                <div class="relative" x-data="{ open: false }">
                                    <button
                                        type="button"
                                        @click="open = !open"
                                        class="w-full border px-4 pr-8 py-2 rounded-lg flex justify-between items-center bg-white hover:bg-gray-50"
                                    >
                                        <div class="flex items-center gap-2">
                                            @if($editCategory)
                                                @php
                                                    $selectedCat = $categories->firstWhere('id', $editCategory);
                                                @endphp
                                                @if($selectedCat && $selectedCat->path)
                                                    <img src="{{ asset('img/categoryIcons/' . $selectedCat->path) }}" class="w-5 h-5">
                                                @endif
                                                {{ $selectedCat?->name ?? 'Pilih kategori' }}
                                            @else
                                                <span class="text-gray-500">Pilih kategori</span>
                                            @endif
                                        </div>
                                        <svg class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 text-gray-600" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div
                                        x-show="open"
                                        @click.outside="open = false"
                                        class="absolute mt-1 w-full bg-white shadow-lg rounded-lg border z-50"
                                    >
                                        @foreach ($categories as $category)
                                            <button
                                                type="button"
                                                wire:click="$set('editCategory', {{ $category->id }})"
                                                @click="open = false"
                                                class="w-full px-4 py-2 flex items-center gap-2 hover:bg-gray-100 text-left"
                                            >
                                                @if($category->path)
                                                    <img src="{{ asset('img/categoryIcons/' . $category->path) }}" class="w-5 h-5">
                                                @endif
                                                {{ $category->name }}
                                            </button>
                                        @endforeach
                                        <div class="border-t my-1"></div>
                                        <button
                                            type="button"
                                            @click="open = false; $wire.openCategoryModal()"
                                            class="w-full px-4 py-2 bg-[#0F1627] hover:bg-[#0F1627]/75 text-white text-left"
                                        >
                                            + Tambah kategori
                                        </button>
                                    </div>
                                </div>
                                @error('editCategory') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Tipe Tugas</label>
                                <div class="relative" x-data="{ open: false }">
                                    <button
                                        type="button"
                                        @click="open = !open"
                                        class="w-full border px-4 pr-8 py-2 rounded-lg flex justify-between items-center bg-white hover:bg-gray-50"
                                    >
                                        <div class="flex items-center gap-2">
                                            @if($editType)
                                                @php
                                                    $selectedType = $types->firstWhere('id', $editType);
                                                @endphp
                                                @if($selectedType)
                                                    @php
                                                        $color = $selectedType->color;
                                                        $textColor = $color >= 178 ? '#000000' : '#FFFFFF';
                                                    @endphp
                                                    <div class="px-3 py-1 rounded-full text-sm font-medium" style="background-color: rgb({{ $color }}, {{ $color }}, {{ $color }}) !important; color: {{ $textColor }} !important;">{{ $selectedType?->category }}</div>
                                                @endif
                                            @else
                                                <span class="text-gray-500">Pilih tipe</span>
                                            @endif
                                        </div>
                                        <svg class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 text-gray-600" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div
                                        x-show="open"
                                        @click.outside="open = false"
                                        class="absolute mt-1 w-full bg-white shadow-lg rounded-lg border z-50"
                                    >
                                        @foreach ($types as $type)
                                            @php
                                                $color = $type->color;
                                                $textColor = $color >= 178 ? '#000000' : '#FFFFFF';
                                            @endphp
                                            <button
                                                type="button"
                                                wire:click="$set('editType', {{ $type->id }})"
                                                @click="open = false"
                                                class="w-full px-4 py-2 hover:bg-gray-100 text-left"
                                            >
                                                <div class="flex items-center gap-2">
                                                    <div class="px-3 py-1 rounded-full text-sm font-medium" style="background-color: rgb({{ $color }}, {{ $color }}, {{ $color }}) !important; color: {{ $textColor }} !important;">{{ $type->category }}</div>
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
                                @error('editType') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Deskripsi</label>
                            <textarea wire:model="editDescription" rows="4" class="w-full border rounded-lg px-3 py-2" required></textarea>
                            @error('editDescription') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-sm font-medium mb-1">Prioritas</label>
                                <select wire:model="editPriority" class="w-full border rounded-lg px-3 py-2" required>
                                    @foreach(\App\Models\Priority::all() as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                                @error('editPriority') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Tanggal</label>
                                <input type="date" wire:model="editDueDate" class="w-full border rounded-lg px-3 py-2" required>
                                @error('editDueDate') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Waktu</label>
                                <input type="time" wire:model="editDueTime" class="w-full border rounded-lg px-3 py-2" required>
                                @error('editDueTime') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" wire:click="closeEdit" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-[#0F1627] text-white rounded-lg hover:bg-gray-800">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- ADD CATEGORY MODAL --}}
        @if($showCategoryModal)
            <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-xl w-[350px] shadow-xl">
                    <div class="flex justify-between mb-3">
                        <h2 class="text-lg font-semibold">Tambah Kategori</h2>
                        <button type="button" wire:click="closeCategoryModal" class="text-gray-600 hover:text-black">✖</button>
                    </div>

                    <div class="space-y-4">
                        <input
                            type="text"
                            wire:model="newCategoryName"
                            class="w-full border px-3 py-2 rounded-lg"
                            placeholder="Nama kategori"
                        />
                        @error('newCategoryName') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror

                        <div>
                            <p class="font-medium mb-2">Pilih Icon</p>
                            <div class="grid grid-cols-5 gap-3">
                                @foreach ($icons ?? [] as $icon)
                                    <div
                                        wire:click="$set('newCategoryIcon', '{{ $icon }}')"
                                        class="p-2 border rounded-xl cursor-pointer hover:bg-gray-50 {{ ($newCategoryIcon ?? '') === $icon ? 'bg-blue-100 border-gray-600' : '' }}"
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

        {{-- ADD TYPE MODAL --}}
        @if($showTypeModal)
            <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-xl w-[350px] shadow-xl">
                    <div class="flex justify-between mb-3">
                        <h2 class="text-lg font-semibold">Tambah Tipe Tugas</h2>
                        <button type="button" wire:click="closeTypeModal" class="text-gray-600 hover:text-black">✖</button>
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
                            type="range"
                            min="0"
                            max="255"
                            wire:model="newTypeColor"
                            class="grayscale-range w-full cursor-pointer"
                        />
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

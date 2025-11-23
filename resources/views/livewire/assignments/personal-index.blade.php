@php use Carbon\Carbon; @endphp

<div class="w-full p-4" x-data>

    {{-- SEARCH BAR --}}
    <div class="w-100">
        <flux:input
            class="mb-4"
            icon="magnifying-glass"
            placeholder="Cari Tugas"
            wire:model.live="search"
        />
    </div>

    {{-- FILTER & SORT --}}
    <div class="flex items-center gap-4 mb-4">

        {{-- FILTER --}}
        <div x-data="{ open:false }" class="relative">
            <button @click="open=!open" class="text-sm font-semibold flex items-center gap-1">
                + Filter
            </button>

            <div x-show="open" @click.outside="open=false"
                 class="absolute z-50 bg-white shadow-lg rounded-lg border p-4 w-48 mt-2">

                {{-- PRIORITY --}}
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

                {{-- CATEGORY --}}
                <div class="mb-3">
                    <p class="text-xs font-semibold mb-1">Kategori</p>
                    <select class="w-full border rounded px-2 py-1 text-sm"
                            wire:model.live="filterCategory">
                        <option value="">Semua</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- TYPE --}}
                <div>
                    <p class="text-xs font-semibold mb-1">Tipe Tugas</p>
                    <select class="w-full border rounded px-2 py-1 text-sm"
                            wire:model.live="filterType">
                        <option value="">Semua</option>
                        @foreach($types as $t)
                            <option value="{{ $t->id }}">{{ $t->category ?? $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- ASSIGNMENT TYPE --}}
                <div>
                    <p class="text-xs font-semibold mb-1">Jenis Tugas</p>
                    <select class="w-full border rounded px-2 py-1 text-sm"
                            wire:model.live="filterAssignmentType">
                        <option value="">Semua</option>
                        <option value="personal">Pribadi</option>
                        <option value="group">Grup</option>
                    </select>
                </div>

            </div>
        </div>

        {{-- SORT --}}
        <div x-data="{ open:false }" class="relative">
            <button @click="open=!open" class="text-sm font-semibold flex items-center gap-1">
                + Sort
            </button>

            <div x-show="open" @click.outside="open=false"
                 class="absolute z-50 bg-white shadow-lg rounded-lg border p-4 w-48 mt-2">

                {{-- STATUS --}}
                <div class="mb-3">
                    <p class="text-xs font-semibold mb-1">Status</p>
                    <select class="w-full border rounded px-2 py-1 text-sm"
                            wire:model.live="sortStatus">
                        <option value="">Default</option>
                        <option value="1">Belum dimulai</option>
                        <option value="2">Sedang dikerjakan</option>
                        <option value="3">Selesai</option>
                    </select>
                </div>

                {{-- DEADLINE --}}
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

    {{-- HEADER --}}
    <div class="grid grid-cols-6 font-semibold text-gray-600 px-4 pb-2">
        <div>Status</div>
        <div>Kategori</div>
        <div>Deadline</div>
        <div>Judul</div>
        <div>Prioritas</div>
        <div>Tipe</div>
    </div>

    {{-- LIST --}}
    @foreach($assignments as $a)

        @php
            $isLate = Carbon::parse($a->due_date)->isPast() && $a->status_id != 3;

            $statusColors = [
                1 => 'bg-[#E3E5EA] text-black',
                2 => 'bg-[#0F1627] text-white',
                3 => 'bg-white text-black border border-gray-600'
            ];

            $statusNames = [
                1 => 'Belum dimulai',
                2 => 'sedang dikerjakan',
                3 => 'Selesai'
            ];

            $priorityColors = [
                1 => 'bg-red-700 text-white',
                2 => 'bg-blue-600 text-white',
                3 => 'bg-gray-700 text-white',
            ];

            $percent = $a->status_id == 3 ? 100 : ($a->status_id == 2 ? 50 : 0);
        @endphp

        <div
            class=\"relative bg-white shadow-sm rounded-xl p-4 mb-3 grid grid-cols-6 items-center gap-4 cursor-pointer
            {{ $isLate ? 'bg-red-50 border border-red-300' : '' }}"
            wire:click="openDetail({{ $a->id }})"
        >

            {{-- STATUS --}}
            @if($a->assignment_type === 'personal')
                <div class="relative select-none" x-data="{ statusOpen: false }">

                    <span
                        class="px-3 py-1 text-xs font-semibold rounded-full inline-flex items-center gap-2 cursor-pointer whitespace-nowrap
                        {{ $statusColors[$a->status_id] }}"
                        @click="statusOpen = !statusOpen"
                    >
                        ● {{ $statusNames[$a->status_id] }}
                    </span>

                    <div class="absolute mt-2 left-0 bg-white rounded-xl shadow-xl w-48 p-3 z-50 border"
                         x-show="statusOpen"
                         @click.outside="statusOpen = false">

                        <div class="text-xs text-gray-600 mb-2">Terpilih</div>

                        <div class="px-3 py-2 rounded-lg text-sm mb-3 {{ $statusColors[$a->status_id] }}">
                            ● {{ $statusNames[$a->status_id] }}
                        </div>

                        <div class="text-xs text-gray-600 mb-2">Pilihan lain</div>

                        @foreach([1,2,3] as $id)
                            @if($id !== $a->status_id)
                                <div wire:click="updateStatus({{ $a->id }}, {{ $id }})"
                                     @click="statusOpen = false"
                                     class="px-3 py-2 rounded-lg text-sm cursor-pointer hover:bg-gray-100 flex items-center gap-2">
                                    ● {{ $statusNames[$id] }}
                                </div>
                            @endif
                        @endforeach

                    </div>

                </div>
            @else
                <span class="px-3 py-1 text-xs font-semibold rounded-full inline-flex items-center gap-2 whitespace-nowrap bg-[#0F1627] text-white">
                    ● Group
                </span>
            @endif

            {{-- CATEGORY --}}
            <div class="flex items-center gap-2">
                @if($a->category?->path)
                    <img src="{{ asset('img/categoryIcons/'.$a->category->path) }}" class="w-6 h-6">
                @endif
                <span class="font-semibold">{{ $a->category?->name ?? '-' }}</span>
            </div>

            {{-- DEADLINE --}}
            <div class="font-semibold {{ $isLate ? 'text-red-600' : '' }}">
                {{ Carbon::parse($a->due_date)->translatedFormat('j F Y H:i') }}
            </div>

            {{-- TITLE --}}
            <div class="font-semibold">{{ $a->title }}</div>

            {{-- PRIORITY --}}
            <div>
                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $priorityColors[$a->priority_id] }}">
                    {{ $a->priority?->name }}
                </span>
            </div>

            {{-- TYPE --}}
            <div>
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-900 text-white inline-flex items-center gap-2">
                    ● {{ $a->typeAssignment?->category ?? $a->typeAssignment?->name ?? '-' }}
                </span>
            </div>

        </div>

    @endforeach

    {{-- DETAIL MODAL --}}
    @if($detailOpen)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white w-[600px] rounded-2xl shadow-xl p-8 relative">

                <button class="absolute top-4 right-4 text-2xl text-gray-600 hover:text-black"
                        wire:click="closeDetail">
                    ×
                </button>

                <h2 class="text-2xl font-bold mb-4">{{ $detailData->title }}</h2>

                <div class="mb-6">
                    <span class="font-semibold text-gray-700">Deadline:</span>
                    <span class="text-gray-600">
                        {{ Carbon::parse($detailData->due_date)->translatedFormat('j F Y | H:i') }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div>
                        <span class="font-bold text-gray-800">Kategori:</span>
                        {{ $detailData->category?->name ?? '-' }}
                    </div>

                    <div>
                        <span class="font-bold text-gray-800">Prioritas:</span>
                        {{ $detailData->priority?->name ?? '-' }}
                    </div>

                    <div>
                        <span class="font-bold text-gray-800">Tipe Tugas:</span>
                        {{ $detailData->typeAssignment?->category ?? $detailData->typeAssignment?->name ?? '-' }}
                    </div>
                </div>

                <div class="mt-6">
                    <div class="font-bold text-gray-800 mb-1">Deskripsi:</div>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $detailData->description }}
                    </p>
                </div>

            </div>
        </div>
    @endif

</div>

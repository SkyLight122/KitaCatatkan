<div class="w-full p-4" x-data>

    {{-- top bar: search + create --}}
    <div class="flex items-center gap-3 mb-4">
        <input type="text" wire:model.debounce.300ms="search"
               class="border rounded px-3 py-2 w-full"
               placeholder="Cari tugas...">

        <div class="flex items-center gap-2">
            <select wire:model="sortBy" class="border rounded px-2 py-2">
                <option value="due">Deadline</option>
                <option value="title">Judul</option>
                <option value="priority_id">Prioritas</option>
            </select>
            <button wire:click="$set('sortDirection', sortDirection === 'asc' ? 'desc' : 'asc')"
                    class="px-3 py-2 rounded bg-gray-200">
                Sort: {{ $sortDirection }}
            </button>
            <button wire:click="$set('openCreateModal', true)" class="px-4 py-2 bg-[#0F1627] text-white rounded">Tambah Tugas</button>
        </div>
    </div>

    {{-- filters: source, category, type, priority --}}
    <div class="flex gap-3 items-center mb-4">
        <div>
            <button wire:click="$set('filterSource','all')"
                    class="px-3 py-1 rounded {{ $filterSource=='all' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">Semua</button>
            <button wire:click="$set('filterSource','personal')"
                    class="px-3 py-1 rounded {{ $filterSource=='personal' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">Pribadi</button>
            <button wire:click="$set('filterSource','group')"
                    class="px-3 py-1 rounded {{ $filterSource=='group' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">Group</button>
        </div>

        <div class="flex items-center gap-2">
            <select wire:model="filterCategory" class="border rounded px-2 py-1">
                <option value="">Semua Kategori (Pribadi)</option>
                @foreach($personalCategories as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>

            <button wire:click="$set('openCategoryModal', true)" class="px-2 py-1 bg-gray-100 rounded">+ Kategori</button>

            <select wire:model="filterType" class="border rounded px-2 py-1">
                <option value="">Semua Tipe (Pribadi)</option>
                @foreach($personalTypes as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                @endforeach
            </select>

            <button wire:click="$set('openTypeModal', true)" class="px-2 py-1 bg-gray-100 rounded">+ Tipe</button>

            <select wire:model="new_priority_id" class="border rounded px-2 py-1">
                @foreach($priorities as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- header grid like group --}}
    <div class="grid grid-cols-7 font-semibold text-gray-600 px-4 pb-2">
        <div>Status</div>
        <div>Kategori</div>
        <div>Deadline</div>
        <div>Judul</div>
        <div>Prioritas</div>
        <div>Tipe Tugas</div>
        <div>Data (anggota yang selesai)</div>
    </div>

    {{-- rows --}}
    <div class="mt-2">
        @foreach($assignments as $item)
            @php
                // compute percent for group tasks; for personal use status only (no group users)
                $percent = 0;
                if($item['source'] === 'group') {
                    $total = $item['raw']->group->users->count() ?? 1;
                    $done = $item['raw']->users()->wherePivot('status_id', 3)->count() ?? 0;
                    $percent = intval(($done / max($total,1)) * 100);
                }
                // status badge classes matching reference:
                $statusClass = match(strtolower($item['status'])) {
                    'belum dimulai' => 'bg-gray-700 text-white',
                    'sedang dikerjakan' => 'bg-[#0F1627] text-white', // your requested color
                    'selesai' => 'bg-white text-black border',
                    default => 'bg-gray-700 text-white'
                };
            @endphp

            <div
                class="relative bg-white shadow-sm rounded-xl p-4 mb-3 grid grid-cols-7 items-center gap-4 cursor-pointer"
                {{-- click row to open detail except when clicking status --}}
                wire:click="openDetail({{ json_encode($item) }})"
            >

                {{-- STATUS column (stop click to prevent opening detail) --}}
                <div class="relative" wire:click.stop>
                    <div class="inline-block">
                        <div
                            class="px-3 py-1 text-sm font-semibold rounded-full inline-flex items-center gap-2 cursor-pointer {{ $statusClass }}"
                            @click.prevent="$wire.openStatusDropdown({{ $item['id'] }}, '{{ $item['source'] }}')"
                        >
                            <span class="w-2 h-2 rounded-full bg-white"></span>
                            {{ $item['status'] }}
                        </div>

                        {{-- status dropdown --}}
                        @if($openStatusFor === $item['source'] . '_' . $item['id'])
                            <div class="absolute z-50 mt-2 left-0 bg-white rounded-xl shadow-xl w-48 p-3 border"
                                 x-data @click.outside="$wire.openStatusFor = null">
                                <div class="text-xs text-gray-600 mb-2">Terpilih</div>
                                <div class="px-3 py-2 rounded-lg text-sm mb-3 {{ $statusClass }}">{{ $item['status'] }}</div>
                                <div class="text-xs text-gray-600 mb-2">Pilihan lain</div>

                                @if(strtolower($item['status']) !== 'belum dimulai')
                                    <div wire:click="updateStatus({{ $item['id'] }}, '{{ $item['source'] }}', 1)"
                                         class="px-3 py-2 rounded-lg text-sm cursor-pointer flex items-center gap-2 hover:bg-gray-100">● Belum dimulai</div>
                                @endif

                                @if(strtolower($item['status']) !== 'sedang dikerjakan')
                                    <div wire:click="updateStatus({{ $item['id'] }}, '{{ $item['source'] }}', 2)"
                                         class="px-3 py-2 rounded-lg text-sm cursor-pointer flex items-center gap-2 hover:bg-gray-100">● sedang dikerjakan</div>
                                @endif

                                @if(strtolower($item['status']) !== 'selesai')
                                    <div wire:click="updateStatus({{ $item['id'] }}, '{{ $item['source'] }}', 3)"
                                         class="px-3 py-2 rounded-lg text-sm cursor-pointer flex items-center gap-2 hover:bg-gray-100">● Selesai</div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- CATEGORY --}}
                <div class="flex items-center gap-2">
                    @if($item['category_icon'])
                        <img src="{{ $item['category_icon'] ? asset('img/categoryIcons/' . $item['category_icon']) : '' }}" class="w-6 h-6 rounded" alt="">
                    @endif
                    <span class="font-semibold">{{ $item['category'] ?? '-' }}</span>
                </div>

                {{-- DEADLINE (red if late) --}}
                <div class="font-semibold">
                    @php
                        $isLate = \Carbon\Carbon::parse($item['due'])->isPast();
                    @endphp
                    <span class="{{ $isLate ? 'text-red-600' : '' }}">
                        {{ \Carbon\Carbon::parse($item['due'])->translatedFormat('j F Y') }}
                    </span>
                </div>

                {{-- TITLE --}}
                <div class="font-semibold">{{ $item['title'] }}</div>

                {{-- PRIORITY --}}
                <div>
                    @php
                        $priorityColors = [
                            1 => 'bg-red-700 text-white',
                            2 => 'bg-blue-600 text-white',
                            3 => 'bg-gray-700 text-white',
                        ];
                    @endphp
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $priorityColors[$item['priority_id']] ?? 'bg-gray-400 text-white' }}">
                        {{ $item['priority'] ?? '-' }}
                    </span>
                </div>

                {{-- TYPE --}}
                <div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-900 text-white inline-flex items-center gap-2">
                        ● {{ $item['type'] ?? '-' }}
                    </span>
                </div>

                {{-- PROGRESS (for group) or placeholder for personal --}}
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold">{{ $item['source'] === 'group' ? $percent . '%' : ($item['status'] === 'Selesai' ? '100%' : '0%') }}</span>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-black rounded-full" style="width: {{ $item['source'] === 'group' ? $percent : ($item['status'] === 'Selesai' ? 100 : 0) }}%"></div>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

    {{-- DETAIL MODAL --}}
    @if($detailOpen)
        <div class="fixed inset-0 bg-black/50 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded shadow-xl w-96 relative">
                <button class="absolute top-3 right-3 text-gray-600" wire:click="closeDetail">✕</button>
                <h2 class="text-2xl font-semibold mb-2">{{ $detailData['title'] }}</h2>
                <p class="text-sm text-gray-600 mb-3"><strong>Deadline:</strong> {{ \Carbon\Carbon::parse($detailData['due'])->translatedFormat('d F Y | H:i') }}</p>
                <p><strong>Kategori:</strong> {{ $detailData['category'] }}</p>
                <p><strong>Prioritas:</strong> {{ $detailData['priority'] }}</p>
                <p><strong>Tipe:</strong> {{ $detailData['type'] }}</p>
                <div class="mt-4">
                    <strong>Deskripsi:</strong>
                    <p class="mt-2 text-sm text-gray-700">{{ $detailData['raw']->description ?? '-' }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- CREATE PERSONAL MODAL --}}
    @if($openCreateModal)
        <div class="fixed inset-0 bg-black/50 flex justify-center items-start z-50 pt-20">
            <div class="bg-white p-6 rounded shadow-xl w-[700px] relative">
                <button class="absolute top-3 right-3 text-gray-600" wire:click="closeCreate">✕</button>
                <h3 class="text-xl font-semibold mb-3">Tambah Tugas Pribadi</h3>

                <form wire:submit.prevent="createPersonal" class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium">Judul</label>
                        <input type="text" wire:model.defer="new_title" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium">Kategori</label>
                            <select wire:model="new_category_id" class="w-full border rounded px-2 py-2">
                                <option value="">Pilih kategori</option>
                                @foreach($personalCategories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Tipe</label>
                            <select wire:model="new_type_id" class="w-full border rounded px-2 py-2">
                                <option value="">Pilih tipe</option>
                                @foreach($personalTypes as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Deskripsi</label>
                        <textarea wire:model.defer="new_description" rows="4" class="w-full border rounded px-3 py-2"></textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium">Prioritas</label>
                            <select wire:model="new_priority_id" class="w-full border rounded px-2 py-2">
                                @foreach($priorities as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Tanggal</label>
                            <input type="date" wire:model="new_due_date_date" class="w-full border rounded px-2 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Waktu</label>
                            <input type="time" wire:model="new_due_date_time" class="w-full border rounded px-2 py-2">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="closeCreate" class="px-4 py-2 rounded border">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded bg-[#0F1627] text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- CREATE CATEGORY MODAL --}}
    @if($openCategoryModal)
        <div class="fixed inset-0 bg-black/50 flex justify-center items-center z-50">
            <div class="bg-white p-4 rounded shadow-md w-96">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold">Tambah Kategori Pribadi</h3>
                    <button wire:click="closeCategoryModal">✕</button>
                </div>

                <div class="space-y-2">
                    <input type="text" wire:model.defer="newCategoryName" placeholder="Nama kategori" class="w-full border rounded px-2 py-2">
                    <input type="text" wire:model.defer="newCategoryIcon" placeholder="Nama file icon (optional)" class="w-full border rounded px-2 py-2">
                    <div class="flex justify-end gap-2">
                        <button wire:click="closeCategoryModal" class="px-3 py-1 border rounded">Batal</button>
                        <button wire:click="createCategory" class="px-3 py-1 bg-[#0F1627] text-white rounded">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- CREATE TYPE MODAL --}}
    @if($openTypeModal)
        <div class="fixed inset-0 bg-black/50 flex justify-center items-center z-50">
            <div class="bg-white p-4 rounded shadow-md w-96">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold">Tambah Tipe Pribadi</h3>
                    <button wire:click="closeTypeModal">✕</button>
                </div>

                <div class="space-y-2">
                    <input type="text" wire:model.defer="newTypeName" placeholder="Nama tipe" class="w-full border rounded px-2 py-2">
                    <input type="color" wire:model.defer="newTypeColor" class="w-full h-10 rounded" />
                    <div class="flex justify-end gap-2">
                        <button wire:click="closeTypeModal" class="px-3 py-1 border rounded">Batal</button>
                        <button wire:click="createType" class="px-3 py-1 bg-[#0F1627] text-white rounded">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

@php use App\Models\GroupList; @endphp
<div class="gap-2 ">
    <div>
        <x-layouts.app.topbar>
        </x-layouts.app.topbar>
    </div>
    <div class="flex">
        <div class="w-[22rem] h-152 flex items-start justify-start py-2 px-2 bg-gray-200">
            <div class="bg-white w-80 h-152 p-4">
                <div class="flex items-center justify-between ">
                    <p class="text-3xl font-semibold">{{$group->title}}</p>
                    <flux:icon.pencil-square class="size-7 cursor-pointer hover:text-gray-700"></flux:icon.pencil-square>
                </div>
                <div class="flex gap-1.5 mt-[-2]">
                    <p class="text-md font-semibold text-gray-500">Dibuat</p>
                    <p class="text-md font-semibold">{{($group->created_at)->format('m/d/Y')}}</p>
                </div>
                <div class="gap-1.5 mt-[-2] break-words max-w-[95%]">
                    <p class="text-md font-semibold text-gray-500">Deskripsi</p>
                    <p class="text-md font-semibold">{{$group->description}}</p>
                </div>
                <div class="mt-1">
                    <p class="text-md font-semibold text-gray-500">Anggota</p>

                    @livewire('group.group-member', ['group' => $group])
                </div>
                <div class="gap-1.5 mt-2 w-full relative"
                     x-data="{ code: '{{ $group->key }}', copied: false }">

                    <p class="font-semibold">Group Code</p>

                    <div class="flex justify-between items-center bg-gray-200 rounded-md">
                        <p class="pl-4">{{ $group->key }}</p>
                        <button
                            class="btn btn-primary py-1.5 px-5 bg-[#0F1627] rounded-md text-white hover:bg-gray-700 hover:cursor-copy"
                            x-on:click="
                navigator.clipboard.writeText(code);
                copied = true;
                setTimeout(() => copied = false, 2000);
            "
                        >
                            Copy
                        </button>
                    </div>
                    <div
                        x-show="copied"
                        x-transition
                        class="absolute bottom-[-4rem] left-0 right-0"
                    >
                        <flux:callout
                            class="bg-[#0F1627]/10 border-[#0F1627] mx-4"
                            variant="secondary"
                            icon="information-circle"
                            heading="Berhasil disalin ke clipboard!"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-1 py-2">
            <div>
                @if(session('success'))
                    <div class="alert alert-success fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-md transition-opacity duration-500 ease-in-out">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                        </svg>
                        <span>{{session('success')}}</span>
                    </div>
                @endif
            </div>
            @livewire('group.index', ['group_id' => $group->id])
            @if($isAdmin)
                <div>
                    <flux:link wire:click.prevent="$dispatch('open-create-modal')" href="#" variant="subtle">Tambah Tugas +</flux:link>
                    @livewire('group.create', ['group' => $group])
                </div>
            @else
                <div class="px-4 py-3 bg-blue-50 border border-blue-200 rounded-lg text-blue-800 text-sm">
                    Hanya admin yang dapat menambah tugas. Hubungi admin grup untuk menambah tugas baru.
                </div>
            @endif
        </div>
    </div>
    @livewire('profile')
</div>

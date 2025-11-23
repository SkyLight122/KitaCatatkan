<x-layouts.app.topbar>
    {{ $slot }}
</x-layouts.app.topbar>

<div class="gap-2 flex">

    <div class="w-[22rem] h-152 flex items-start justify-start py-2 px-2 bg-gray-200">
        <div class="bg-white w-80 h-152">
            <div class="flex items-center justify-between px-4 py-2">
                <p class="text-3xl font-semibold">
                    {{ Auth::user()->nama_tabel_tugas }}
                </p>
            </div>

            <div class="flex gap-1.5 pl-4 mt-[-2]">
                <p class="text-md font-semibold text-gray-500">Dibuat</p>
                <p class="text-md font-semibold">
                    {{ Auth::user()->created_at->format('m/d/Y h:i A') }}
                </p>
            </div>

            <div class="pl-4 mt-[10]">
                <p class="text-md font-semibold">Bersama</p>
                <div>
                    @livewire('group.group-lists')
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 py-2">
        @if(session('success'))
            <div class="alert alert-success fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-md">
                {{ session('success') }}
            </div>
        @endif

        @livewire('assignments.index')
        
        @livewire('assignments.create')
    </div>

</div>

{{-- MODAL HARUS DI SINI --}}
@livewire('profile')

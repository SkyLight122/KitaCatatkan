<div class="max-h-[220px] overflow-y-auto">

    <flux:input
        placeholder="Cari Anggota"
        icon="magnifying-glass"
        wire:model.live="searchMember"
        class="w-full mb-2"
    />

    @foreach ($members as $user)
        <div class="flex mb-2">
            <img
                class="size-10 rounded-4xl object-cover"
                src="{{ $user->path ? asset($user->path) : asset('images/profile.png') }}"
                alt="PP"
            >
            <p class="pl-2 font-semibold w-40 break-words leading-tight">
                {{ $user->name }}
            </p>
            <p class="ml-auto mt-5 text-sm font-semibold text-gray-700">
                {{ $user->pivot->isAdmin ? 'Admin' : 'Anggota' }}
            </p>

        </div>
    @endforeach

</div>

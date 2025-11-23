<div>
    @foreach($groups as $group)
        <a href="{{ route('group.show', ['group' => $group->id]) }}">
            <div class="pr-5">

                <div class="flex pl-5 bg-gray-300 hover:bg-gray-400 p-2  mb-1 w-full rounded-sm gap-2 items-center">
                    {{-- NAMA / TITLE --}}
                    <p class=" text-sm font-semibold">
                        {{ $group->title }}
                    </p>
                </div>
            </div>
        </a>
    @endforeach

    <div class="flex gap-2 mt-4 pr-5">
        @livewire('group.joingroup')
        @livewire('group.addgroup')
    </div>
</div>

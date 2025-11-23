<div class="relative">
    <div class="flex items-center cursor-pointer" wire:click="toggleNotificationPanel">
        <div class="relative">
            <flux:icon.bell variant="solid" class="size-8 text-[#0F1627]"></flux:icon.bell>
            @if($unreadCount > 0)
                <div class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </div>
            @endif
        </div>
    </div>

    @if($showNotificationPanel)
        <div class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-2xl border border-gray-200 z-50 max-h-96 overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 p-4 rounded-t-xl">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-[#0F1627]">Notifikasi</h3>
                    <button
                        wire:click="markAsViewed"
                        class="text-gray-500 hover:text-gray-700 text-xl"
                    >
                        ✕
                    </button>
                </div>
            </div>

            @if($notifications->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($notifications as $notif)
                        <div class="p-4 hover:bg-gray-50 transition cursor-pointer border-l-4 border-yellow-400">
                            <div class="flex justify-between items-start gap-3">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $notif->title }}</p>
                                    <p class="text-sm text-gray-600 mt-1">Deadline: {{ is_string($notif->due_date) ? \Carbon\Carbon::parse($notif->due_date)->format('d M Y H:i') : $notif->due_date->format('d M Y H:i') }}</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        @if($notif->category)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                                @if($notif->category->path)
                                                    <img src="{{ asset('img/categoryIcons/' . $notif->category->path) }}" class="w-3 h-3">
                                                @endif
                                                {{ $notif->category->name }}
                                            </span>
                                        @endif
                                        @if($notif->typeAssignment)
                                            @php
                                                $colorValue = $notif->typeAssignment->color;
                                                $colorRgb = "rgb($colorValue, $colorValue, $colorValue)";
                                            @endphp
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs text-white" style="background-color: {{ $colorRgb }};">
                                                {{ $notif->typeAssignment->category }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center text-gray-500">
                    <p class="text-sm">Tidak ada notifikasi</p>
                </div>
            @endif
        </div>

        @if($showNotificationPanel)
            <div class="fixed inset-0 z-40" wire:click="markAsViewed"></div>
        @endif
    @endif
</div>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="bg-gray-200">
<div class="h-[70px] w-full bg-white flex items-center justify-between px-9">
    <a href="{{ route('dashboard') }}">
        <img class="w-[120px]" src="{{ asset('images/logo(hitam).png') }}">
    </a>
    <div class="flex-1"></div>
    <div class="flex items-center gap-6">
        @livewire('notification')
        <div class="flex items-center">
            @php
                $user = Auth::user();
                $profilePhoto =
                    $user->path && file_exists(public_path($user->path))
                    ? asset($user->path)
                    : asset('images/profile.png');
            @endphp
            <div
                class="border-2 border-black p-2 rounded-xl flex items-center gap-4 cursor-pointer hover:bg-gray-300"
                onclick="window.dispatchEvent(new CustomEvent('open-profile'))"
            >
                <img
                    class="w-8 h-8 rounded-full object-cover"
                    src="{{ $profilePhoto }}"
                >

                <div class="pr-6 leading-tight">
                    <p class="font-bold text-sm">{{ $user->name }}</p>
                    <p class="text-[11px] -mt-1">{{ $user->email }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        @fluxAppearance
    </head>
    <body class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-800 to-slate-600 flex items-center justify-center">
        <div class=" flex justify-center py-20">
            <div class=" bg-white w-160 p-[20px] pl-25 pr-25 rounded-lg antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
                <div class="flex flex-col gap-6">
                    <div class="px-10 py-8">{{ $slot }}</div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>

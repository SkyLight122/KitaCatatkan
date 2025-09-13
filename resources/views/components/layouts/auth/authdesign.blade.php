<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        @fluxAppearance
    </head>
    <body class="min-h-screen h-screen overflow-hidden bg-gradient-to-br from-slate-950 via-slate-800 to-slate-600 flex items-center justify-center">
        <div class="scale-90 origin-center">
            <div class=" bg-white scale-90 w-160 p-[30px] pl-[85px] pr-[85px] rounded-lg shadow-[0_0_20px_7px_rgba(170,170,200,0.8)] antialiased dark:bg-gradient-to-b dark:from-neutral-900 dark:to-neutral-800">
                <div class="flex flex-col gap-6">
                    <div class="px-10 py-8">{{ $slot }}</div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>

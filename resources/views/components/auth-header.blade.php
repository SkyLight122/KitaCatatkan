@props([
    'title',
    'description',
])

<div class="flex w-full flex-col">
    <flux:heading size="xl" >{{ $title }}</flux:heading>
    <flux:subheading class="text-justify">{{ $description }}</flux:subheading>
</div>

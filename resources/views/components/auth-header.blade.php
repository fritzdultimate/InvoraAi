@props([
    'title',
    'description',
])

<div class="flex w-full flex-col gap-1.5 text-center">
    <flux:heading size="lg">{{ $title }}</flux:heading>
    <flux:subheading>{{ $description }}</flux:subheading>
</div>

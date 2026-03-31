@props([
    'status',
])

@if ($status)
    <div
        {{ $attributes->merge([
            'class' => 'rounded-lg border border-[#00b08b]/20 bg-[#00b08b]/[0.07] px-3 py-2.5 text-center text-sm font-medium text-[#a8dccf]',
        ]) }}
    >
        {{ $status }}
    </div>
@endif

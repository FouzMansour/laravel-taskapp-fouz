@props(['status'])

@php
    $colors = [
        'pending' => 'bg-gray-100 text-gray-800',
        'completed' => 'bg-blue-100 text-blue-800',
        'approved' => 'bg-green-100 text-green-800',
        'rejected' => 'bg-red-100 text-red-800',
    ];
@endphp

<span class="px-2 py-1 text-xs font-semibold rounded-full {{ $colors[$status->value] }}">
    {{ ucfirst($status->value) }}
</span>
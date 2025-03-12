@props(['active' => false, 'href' => '#'])

@php
$classes = $active
    ? 'flex items-center px-4 py-2 text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-800 dark:text-gray-200'
    : 'flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-400';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if (isset($icon))
        <div class="w-5 h-5 mr-3">
            {{ $icon }}
        </div>
    @endif
    
    <span class="flex-1">{{ $slot }}</span>
</a> 
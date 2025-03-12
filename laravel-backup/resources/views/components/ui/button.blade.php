@props([
    'variant' => 'primary',
    'size' => 'md',
    'as' => 'button',
    'href' => null,
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'icon' => null
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900';

$variants = [
    'primary' => 'text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 disabled:bg-indigo-400',
    'secondary' => 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-indigo-500 disabled:bg-gray-100 dark:text-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700',
    'danger' => 'text-white bg-red-600 hover:bg-red-700 focus:ring-red-500 disabled:bg-red-400',
    'success' => 'text-white bg-green-600 hover:bg-green-700 focus:ring-green-500 disabled:bg-green-400',
];

$sizes = [
    'xs' => 'px-2.5 py-1.5 text-xs rounded',
    'sm' => 'px-3 py-2 text-sm leading-4 rounded-md',
    'md' => 'px-4 py-2 text-sm rounded-md',
    'lg' => 'px-4 py-2 text-base rounded-md',
    'xl' => 'px-6 py-3 text-base rounded-md',
];

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
if ($loading) {
    $classes .= ' cursor-wait opacity-75';
}
if ($disabled) {
    $classes .= ' cursor-not-allowed';
}
@endphp

@if ($as === 'a' && $href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($loading)
            <svg class="w-4 h-4 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif ($icon)
            <span class="mr-2">{{ $icon }}</span>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes, 'disabled' => $disabled || $loading]) }}>
        @if ($loading)
            <svg class="w-4 h-4 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif ($icon)
            <span class="mr-2">{{ $icon }}</span>
        @endif
        {{ $slot }}
    </button>
@endif 
@props([
    'header' => null,
    'footer' => null,
    'padding' => true,
    'hover' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700' . ($hover ? ' transition-shadow duration-200 hover:shadow-lg' : '')]) }}>
    @if ($header)
        <div class="px-4 py-5 border-b border-gray-200 dark:border-gray-700 sm:px-6">
            {{ $header }}
        </div>
    @endif

    <div @class([
        'px-4 py-5 sm:p-6' => $padding,
    ])>
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700 sm:px-6">
            {{ $footer }}
        </div>
    @endif
</div> 
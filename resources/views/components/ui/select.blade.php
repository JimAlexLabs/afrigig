@props([
    'label' => null,
    'error' => null,
    'disabled' => false,
    'helper' => null,
    'placeholder' => null,
])

<div>
    @if ($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
        </label>
    @endif

    <select
        {{ $attributes->merge([
            'class' => 'block w-full rounded-md ' . 
                ($error ? 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500') .
                ($disabled ? ' bg-gray-100 cursor-not-allowed' : '') .
                ' dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-indigo-500 dark:focus:border-indigo-500'
        ]) }}
        @disabled($disabled)
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        {{ $slot }}
    </select>

    @if ($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @elseif ($helper)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $helper }}</p>
    @endif
</div> 
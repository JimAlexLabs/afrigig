@props([
    'type' => 'text',
    'label' => null,
    'error' => null,
    'leadingIcon' => null,
    'trailingIcon' => null,
    'disabled' => false,
    'readonly' => false,
    'helper' => null,
])

<div>
    @if ($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
        </label>
    @endif

    <div class="relative rounded-md shadow-sm">
        @if ($leadingIcon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                {{ $leadingIcon }}
            </div>
        @endif

        <input
            type="{{ $type }}"
            {{ $attributes->merge([
                'class' => 'block w-full rounded-md ' . 
                    ($error ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500') .
                    ($leadingIcon ? ' pl-10' : '') .
                    ($trailingIcon ? ' pr-10' : '') .
                    ($disabled ? ' bg-gray-100 cursor-not-allowed' : '') .
                    ' dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-indigo-500 dark:focus:border-indigo-500'
            ]) }}
            @disabled($disabled)
            @readonly($readonly)
        >

        @if ($trailingIcon)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                {{ $trailingIcon }}
            </div>
        @endif
    </div>

    @if ($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @elseif ($helper)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $helper }}</p>
    @endif
</div> 
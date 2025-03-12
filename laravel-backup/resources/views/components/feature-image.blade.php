@props(['imageUrl', 'alt' => '', 'class' => ''])

@if($imageUrl)
    <div {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-lg shadow-lg ' . $class]) }}>
        <img 
            src="{{ $imageUrl }}" 
            alt="{{ $alt }}" 
            class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
            loading="lazy"
            onerror="console.error('Failed to load image:', this.src); this.style.display='none'; this.nextElementSibling.style.display='block';"
        >
        <div class="hidden p-4 bg-red-50 text-red-600 rounded-lg">
            <p class="text-sm">Failed to load image. Please try refreshing the page.</p>
            @if(config('app.debug'))
                <p class="text-xs mt-2">Image URL: {{ $imageUrl }}</p>
            @endif
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
    </div>
@else
    @if(config('app.debug'))
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
            <p class="font-bold">Debug Info:</p>
            <p>No image URL provided for: {{ $alt }}</p>
            <p class="text-xs mt-2">Component called from: {{ debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['file'] ?? 'Unknown' }}</p>
        </div>
    @endif
@endif 
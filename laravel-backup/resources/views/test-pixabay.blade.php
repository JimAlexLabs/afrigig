<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pixabay API Test') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Configuration Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Configuration Status</h3>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600">
                            API Key Configured: 
                            @if($config['api_key_configured'])
                                <span class="text-green-600">Yes</span>
                            @else
                                <span class="text-red-600">No</span>
                            @endif
                        </p>
                        <p class="text-sm text-gray-600">
                            API Key Length: {{ $config['api_key_length'] }} characters
                        </p>
                        <p class="text-sm text-gray-600">
                            API Key Preview: {{ $config['api_key_preview'] }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Last Error (if any) -->
            @if($lastError)
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                    <p class="font-bold">Last Error:</p>
                    <p>{{ $lastError }}</p>
                </div>
            @endif

            <!-- Test Results -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Test Results</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($tests as $type => $image)
                            <div class="space-y-4">
                                <h4 class="font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $type) }}</h4>
                                @if($image)
                                    <x-feature-image 
                                        :imageUrl="$image['largeImageURL']" 
                                        :alt="str_replace('_', ' ', $type)"
                                        class="h-48"
                                    />
                                    <div class="text-xs text-gray-500 mt-2">
                                        <p>Preview URL: {{ $image['previewURL'] }}</p>
                                        <p>Large URL: {{ $image['largeImageURL'] }}</p>
                                    </div>
                                @else
                                    <div class="bg-yellow-50 text-yellow-700 p-4 rounded">
                                        No image found
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
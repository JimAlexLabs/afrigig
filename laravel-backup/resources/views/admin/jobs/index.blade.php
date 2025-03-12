@inject('pixabay', 'App\Services\PixabayService')

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Job Listings') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($image = $pixabay->getJobListingImage())
                <div class="mb-8">
                    <x-feature-image 
                        :imageUrl="$image['largeImageURL']" 
                        :alt="'Job Listings Feature'"
                        class="h-64 w-full"
                    />
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($jobs->isEmpty())
                        <p class="text-center text-gray-500 py-8">
                            {{ __('No jobs available.') }}
                        </p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($jobs as $job)
                                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h3 class="text-lg font-semibold">{{ $job->title }}</h3>
                                            <p class="text-sm text-gray-500">
                                                Posted by {{ $job->user->name }}
                                            </p>
                                        </div>
                                        <span class="px-3 py-1 text-sm rounded-full 
                                            {{ $job->status === 'open' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $job->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $job->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                            {{ ucfirst($job->status) }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-600 mb-4">
                                        {{ Str::limit($job->description, 100) }}
                                    </p>
                                    
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach($job->skills as $skill)
                                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">
                                                {{ $skill->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                    
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500">
                                            Budget: ${{ number_format($job->budget, 2) }}
                                        </span>
                                        <a href="{{ route('admin.jobs.show', $job) }}" 
                                           class="text-blue-500 hover:text-blue-700">
                                            View Details →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            {{ $jobs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
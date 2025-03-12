<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <h1 class="text-3xl font-bold mb-6">Available Jobs</h1>
                
                <div class="mb-8">
                    <p class="text-gray-600">Browse through our latest job opportunities and find your next project.</p>
                </div>
                
                @if($jobs->count() > 0)
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($jobs as $job)
                            <div class="border rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h2 class="text-xl font-semibold mb-2">{{ $job->title }}</h2>
                                        <p class="text-gray-600 mb-4">{{ Str::limit($job->description, 150) }}</p>
                                        
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $job->category }}</span>
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $job->experience_level }}</span>
                                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $job->project_length }}</span>
                                        </div>
                                        
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Deadline: {{ $job->deadline->format('M d, Y') }}
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-green-600 mb-2">${{ $job->budget_min }} - ${{ $job->budget_max }}</div>
                                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        {{ $jobs->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No jobs found</h3>
                        <p class="mt-1 text-sm text-gray-500">Check back later for new opportunities.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 
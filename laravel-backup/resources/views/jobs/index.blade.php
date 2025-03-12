<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">My Jobs</h2>
                        <a href="{{ route('jobs.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Post New Job
                        </a>
                    </div>

                    <!-- Filters -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('jobs.my') }}" class="flex flex-wrap gap-4">
                            <div class="flex-1 min-w-[200px]">
                                <x-input-label for="search" :value="__('Search')" />
                                <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" :value="request('search')" placeholder="Search by title or description..." />
                            </div>
                            <div class="w-40">
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="w-40">
                                <x-input-label for="sort" :value="__('Sort By')" />
                                <select id="sort" name="sort" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="budget_high" {{ request('sort') == 'budget_high' ? 'selected' : '' }}>Budget (High to Low)</option>
                                    <option value="budget_low" {{ request('sort') == 'budget_low' ? 'selected' : '' }}>Budget (Low to High)</option>
                                    <option value="bids_most" {{ request('sort') == 'bids_most' ? 'selected' : '' }}>Most Bids</option>
                                    <option value="bids_least" {{ request('sort') == 'bids_least' ? 'selected' : '' }}>Least Bids</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <x-primary-button>
                                    {{ __('Filter') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- Jobs List -->
                    <div class="space-y-6">
                        @forelse($jobs as $job)
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900">
                                            <a href="{{ route('jobs.show', $job) }}" class="hover:text-indigo-600">{{ $job->title }}</a>
                                        </h3>
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $job->status_color }}">
                                                {{ ucfirst($job->status) }}
                                            </span>
                                            <span class="mx-2">•</span>
                                            <span>Posted {{ $job->created_at->diffForHumans() }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $job->bids_count }} {{ Str::plural('bid', $job->bids_count) }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900">${{ number_format($job->budget_min) }} - ${{ number_format($job->budget_max) }}</p>
                                        <p class="text-sm text-gray-500">Deadline: {{ $job->deadline->format('M d, Y') }}</p>
                                    </div>
                                </div>

                                <p class="mt-4 text-gray-600">{{ Str::limit($job->description, 200) }}</p>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach($job->skills as $skill)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $skill->name }}
                                        </span>
                                    @endforeach
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    <div class="flex space-x-4">
                                        <a href="{{ route('jobs.show', $job) }}" class="text-sm text-indigo-600 hover:text-indigo-900">View Details</a>
                                        <a href="{{ route('jobs.edit', $job) }}" class="text-sm text-gray-600 hover:text-gray-900">Edit</a>
                                        @if($job->status === 'draft')
                                            <form method="POST" action="{{ route('jobs.publish', $job) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-sm text-green-600 hover:text-green-900">Publish</button>
                                            </form>
                                        @endif
                                        @if($job->status === 'active')
                                            <form method="POST" action="{{ route('jobs.cancel', $job) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-sm text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to cancel this job?')">Cancel</button>
                                            </form>
                                        @endif
                                    </div>
                                    <div class="flex items-center">
                                        @if($job->bids_count > 0)
                                            <a href="{{ route('jobs.bids', $job) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                View Bids
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No jobs found</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating a new job posting.</p>
                                <div class="mt-6">
                                    <a href="{{ route('jobs.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Post New Job
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($jobs->hasPages())
                        <div class="mt-6">
                            {{ $jobs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
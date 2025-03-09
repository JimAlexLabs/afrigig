<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Jobs') }}
            </h2>
            @can('create', App\Models\Job::class)
            <a href="{{ route('jobs.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                {{ __('Post a Job') }}
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('jobs.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <x-input-label for="search" :value="__('Search')" />
                                <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" :value="request('search')" placeholder="Search jobs..." />
                            </div>

                            <div>
                                <x-input-label for="category" :value="__('Category')" />
                                <select id="category" name="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">All Categories</option>
                                    <option value="web" {{ request('category') === 'web' ? 'selected' : '' }}>Web Development</option>
                                    <option value="mobile" {{ request('category') === 'mobile' ? 'selected' : '' }}>Mobile Development</option>
                                    <option value="design" {{ request('category') === 'design' ? 'selected' : '' }}>Design</option>
                                    <option value="writing" {{ request('category') === 'writing' ? 'selected' : '' }}>Writing</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="experience_level" :value="__('Experience Level')" />
                                <select id="experience_level" name="experience_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">All Levels</option>
                                    <option value="entry" {{ request('experience_level') === 'entry' ? 'selected' : '' }}>Entry Level</option>
                                    <option value="intermediate" {{ request('experience_level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="expert" {{ request('experience_level') === 'expert' ? 'selected' : '' }}>Expert</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="budget" :value="__('Budget Range')" />
                                <div class="grid grid-cols-2 gap-2">
                                    <x-text-input id="budget_min" name="budget_min" type="number" class="mt-1 block w-full" :value="request('budget_min')" placeholder="Min" />
                                    <x-text-input id="budget_max" name="budget_max" type="number" class="mt-1 block w-full" :value="request('budget_max')" placeholder="Max" />
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>
                                {{ __('Filter Jobs') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Jobs List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($jobs->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-gray-500">No jobs found matching your criteria.</p>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($jobs as $job)
                                <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-semibold">
                                                <a href="{{ route('jobs.show', $job) }}" class="text-indigo-600 hover:text-indigo-800">
                                                    {{ $job->title }}
                                                </a>
                                            </h3>
                                            <div class="mt-2 text-sm text-gray-600">
                                                <p>{{ Str::limit($job->description, 200) }}</p>
                                            </div>
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                @foreach($job->skills_required as $skill)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        {{ $skill }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-semibold text-gray-900">
                                                ${{ number_format($job->budget_min) }} - ${{ number_format($job->budget_max) }}
                                            </p>
                                            <p class="text-sm text-gray-500">{{ $job->bids_count ?? 0 }} bids</p>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                                        <div class="flex items-center space-x-4">
                                            <span>{{ $job->category }}</span>
                                            <span>{{ ucfirst($job->experience_level) }}</span>
                                            <span>{{ ucfirst($job->project_length) }}</span>
                                        </div>
                                        <div>
                                            Posted {{ $job->created_at->diffForHumans() }}
                                        </div>
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
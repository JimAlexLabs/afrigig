<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $job->title }}
            </h2>
            @if($job->client_id === auth()->id())
                <div class="flex space-x-4">
                    <a href="{{ route('jobs.edit', $job) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        {{ __('Edit Job') }}
                    </a>
                    <form method="POST" action="{{ route('jobs.destroy', $job) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this job?')">
                            {{ __('Delete Job') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Back Button -->
                    <div class="mb-6">
                        <a href="{{ url()->previous() }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back
                        </a>
                    </div>

                    <!-- Job Header -->
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-semibold">{{ $job->title }}</h2>
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

                    <!-- Job Details -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="md:col-span-2">
                            <!-- Description -->
                            <div class="prose max-w-none">
                                <h3 class="text-lg font-semibold mb-4">Job Description</h3>
                                <div class="text-gray-600 space-y-4">
                                    {!! nl2br(e($job->description)) !!}
                                </div>
                            </div>

                            <!-- Required Skills -->
                            <div class="mt-8">
                                <h3 class="text-lg font-semibold mb-4">Required Skills</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($job->skills as $skill)
                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                            {{ $skill->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Attachments -->
                            @if($job->attachments)
                                <div class="mt-8">
                                    <h3 class="text-lg font-semibold mb-4">Attachments</h3>
                                    <div class="space-y-2">
                                        @foreach(json_decode($job->attachments) as $attachment)
                                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                                <svg class="w-6 h-6 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                </svg>
                                                <a href="{{ Storage::url($attachment) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-900">
                                                    {{ basename($attachment) }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div>
                            <!-- Job Details Card -->
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold mb-4">Job Details</h3>
                                <dl class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $job->category->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Experience Level</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($job->experience_level) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Project Length</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ str_replace('_', ' ', ucfirst($job->project_length)) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Posted By</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $job->client->name }}</dd>
                                    </div>
                                </dl>

                                @if(auth()->check())
                                    @if(auth()->user()->isFreelancer() && $job->status === 'active')
                                        @if(!$job->bids()->where('freelancer_id', auth()->id())->exists())
                                            <div class="mt-6">
                                                <a href="{{ route('jobs.bid', $job) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    Submit a Bid
                                                </a>
                                            </div>
                                        @else
                                            <div class="mt-6">
                                                <p class="text-sm text-gray-500 text-center">You have already submitted a bid for this job</p>
                                            </div>
                                        @endif
                                    @endif

                                    @if(auth()->user()->isClient() && $job->client_id === auth()->id())
                                        <div class="mt-6 space-y-3">
                                            <a href="{{ route('jobs.edit', $job) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                                Edit Job
                                            </a>
                                            @if($job->status === 'active')
                                                <form method="POST" action="{{ route('jobs.cancel', $job) }}">
                                                    @csrf
                                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Are you sure you want to cancel this job?')">
                                                        Cancel Job
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endif
                                @else
                                    <div class="mt-6">
                                        <a href="{{ route('login') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Login to Submit a Bid
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Similar Jobs -->
                            @if($similarJobs->count() > 0)
                                <div class="mt-8">
                                    <h3 class="text-lg font-semibold mb-4">Similar Jobs</h3>
                                    <div class="space-y-4">
                                        @foreach($similarJobs as $similarJob)
                                            <a href="{{ route('jobs.show', $similarJob) }}" class="block p-4 bg-white border border-gray-200 rounded-lg hover:border-indigo-500 transition-colors duration-200">
                                                <h4 class="font-medium text-gray-900">{{ $similarJob->title }}</h4>
                                                <p class="mt-1 text-sm text-gray-500">${{ number_format($similarJob->budget_min) }} - ${{ number_format($similarJob->budget_max) }}</p>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
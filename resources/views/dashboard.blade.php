<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- User Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold">Active Jobs</h3>
                            <p class="text-3xl font-bold">
                                @if(auth()->user()->role === 'client')
                                    {{ \App\Models\Job::where('user_id', auth()->id())->where('status', 'active')->count() }}
                                @else
                                    {{ \App\Models\Job::whereHas('bids', function($query) {
                                        $query->where('user_id', auth()->id())->where('status', 'accepted');
                                    })->count() }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold">Total Earnings</h3>
                            <p class="text-3xl font-bold">
                                ${{ number_format(\App\Models\Payment::where('user_id', auth()->id())
                                    ->where('status', 'completed')
                                    ->sum('amount'), 2) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold">Rating</h3>
                            <p class="text-3xl font-bold">{{ number_format(auth()->user()->rating, 1) }} / 5.0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Sections -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if(auth()->user()->role === 'client')
                    <!-- My Posted Jobs -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">My Posted Jobs</h3>
                                <a href="{{ route('jobs.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    Post New Job
                                </a>
                            </div>
                            <div class="space-y-4">
                                @foreach(\App\Models\Job::where('user_id', auth()->id())->latest()->take(5)->get() as $job)
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $job->title }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $job->bids()->count() }} bids • Posted {{ $job->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                ${{ number_format($job->budget, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Recent Bids -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Bids</h3>
                                <a href="{{ route('jobs.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">View All Jobs</a>
                            </div>
                            <div class="space-y-4">
                                @foreach(\App\Models\Bid::whereHas('job', function($query) {
                                    $query->where('user_id', auth()->id());
                                })->with(['user', 'job'])->latest()->take(5)->get() as $bid)
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $bid->user->name }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Bid on {{ $bid->job->title }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    ${{ number_format($bid->amount, 2) }}
                                                </span>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $bid->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Available Jobs -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Available Jobs</h3>
                                <a href="{{ route('jobs.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">View All</a>
                            </div>
                            <div class="space-y-4">
                                @foreach(\App\Models\Job::where('status', 'active')->latest()->take(5)->get() as $job)
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $job->title }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Posted by {{ $job->user->name }} • {{ $job->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                ${{ number_format($job->budget, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- My Active Jobs -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">My Active Jobs</h3>
                            </div>
                            <div class="space-y-4">
                                @foreach(\App\Models\Job::whereHas('bids', function($query) {
                                    $query->where('user_id', auth()->id())->where('status', 'accepted');
                                })->latest()->take(5)->get() as $job)
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $job->title }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Client: {{ $job->user->name }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    In Progress
                                                </span>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    Started {{ $job->updated_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

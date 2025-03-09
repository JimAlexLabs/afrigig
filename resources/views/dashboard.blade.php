<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                @if(auth()->user()->role === 'client')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-gray-500 text-sm font-medium">Posted Jobs</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ auth()->user()->postedJobs->count() }}</div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-gray-500 text-sm font-medium">Active Jobs</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ auth()->user()->postedJobs->where('status', 'in_progress')->count() }}</div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-gray-500 text-sm font-medium">Total Spent</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-900">${{ number_format(auth()->user()->payments->sum('amount'), 2) }}</div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-gray-500 text-sm font-medium">Completed Jobs</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ auth()->user()->postedJobs->where('status', 'completed')->count() }}</div>
                        </div>
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-gray-500 text-sm font-medium">Active Projects</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ auth()->user()->bids->whereIn('status', ['accepted'])->count() }}</div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-gray-500 text-sm font-medium">Submitted Bids</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ auth()->user()->bids->count() }}</div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-gray-500 text-sm font-medium">Total Earned</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-900">${{ number_format(auth()->user()->payments->sum('amount'), 2) }}</div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-gray-500 text-sm font-medium">Success Rate</div>
                            @php
                                $totalBids = auth()->user()->bids->count();
                                $acceptedBids = auth()->user()->bids->where('status', 'accepted')->count();
                                $successRate = $totalBids > 0 ? round(($acceptedBids / $totalBids) * 100) : 0;
                            @endphp
                            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $successRate }}%</div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Activity -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                        @if(auth()->user()->role === 'client')
                            @forelse(auth()->user()->postedJobs->sortByDesc('created_at')->take(5) as $job)
                                <div class="mb-4 last:mb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <a href="{{ route('jobs.show', $job) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">{{ $job->title }}</a>
                                            <p class="text-sm text-gray-500">{{ $job->bids->count() }} bids received</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                            $job->status === 'open' ? 'bg-green-100 text-green-800' :
                                            ($job->status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                                            'bg-gray-100 text-gray-800') 
                                        }}">
                                            {{ ucfirst($job->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500">No recent activity</p>
                            @endforelse
                        @else
                            @forelse(auth()->user()->bids->sortByDesc('created_at')->take(5) as $bid)
                                <div class="mb-4 last:mb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <a href="{{ route('jobs.show', $bid->job) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">{{ $bid->job->title }}</a>
                                            <p class="text-sm text-gray-500">${{ number_format($bid->amount, 2) }} - {{ $bid->delivery_time }} days</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                            $bid->status === 'accepted' ? 'bg-green-100 text-green-800' :
                                            ($bid->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                            'bg-red-100 text-red-800') 
                                        }}">
                                            {{ ucfirst($bid->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500">No recent activity</p>
                            @endforelse
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-4">
                            @if(auth()->user()->role === 'client')
                                <a href="{{ route('jobs.create') }}" class="block w-full text-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                    {{ __('Post a New Job') }}
                                </a>
                                <a href="{{ route('jobs.index') }}" class="block w-full text-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                                    {{ __('View My Jobs') }}
                                </a>
                            @else
                                <a href="{{ route('jobs.index') }}" class="block w-full text-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                    {{ __('Find New Jobs') }}
                                </a>
                                <a href="#" class="block w-full text-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                                    {{ __('View My Bids') }}
                                </a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="block w-full text-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                                {{ __('Update Profile') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
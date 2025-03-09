<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold">Total Users</h3>
                            <p class="text-3xl font-bold">{{ \App\Models\User::count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold">Active Jobs</h3>
                            <p class="text-3xl font-bold">{{ \App\Models\Job::where('status', 'active')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold">Total Earnings</h3>
                            <p class="text-3xl font-bold">${{ number_format(\App\Models\Payment::where('status', 'completed')->sum('amount'), 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold">Pending Verifications</h3>
                            <p class="text-3xl font-bold">{{ \App\Models\User::where('is_verified', false)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Management Sections -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Users -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Users</h3>
                            <a href="{{ route('admin.users') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">View All</a>
                        </div>
                        <div class="space-y-4">
                            @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-500 text-sm">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $user->is_verified ? 'Verified' : 'Pending' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Recent Jobs -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Jobs</h3>
                            <a href="{{ route('admin.jobs') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">View All</a>
                        </div>
                        <div class="space-y-4">
                            @foreach(\App\Models\Job::with('user')->latest()->take(5)->get() as $job)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $job->title }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Posted by {{ $job->user->name }}</p>
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
            </div>
        </div>
    </div>
</x-app-layout> 
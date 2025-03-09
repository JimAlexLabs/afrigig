@inject('pixabay', 'App\Services\PixabayService')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($image = $pixabay->getAdminDashboardImage())
                <div class="mb-8">
                    <x-feature-image 
                        :imageUrl="$image['largeImageURL']" 
                        :alt="'Admin Dashboard Feature'"
                        class="h-64 w-full"
                    />
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Users Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                                <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalUsers }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Jobs Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Jobs</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $activeJobs }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Earnings Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10">
                                <svg class="h-8 w-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Earnings</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($totalEarnings, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed Jobs Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                                <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed Jobs</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $completedJobs }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        @forelse($recentActivities as $activity)
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <span class="inline-block h-10 w-10 rounded-full overflow-hidden bg-gray-100">
                                        @if($activity->user && $activity->user->avatar)
                                            <img src="{{ $activity->user->avatar }}" alt="{{ $activity->user->name }}" class="h-full w-full object-cover">
                                        @else
                                            <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        @endif
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ $activity->description }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 text-center py-4">No recent activity</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
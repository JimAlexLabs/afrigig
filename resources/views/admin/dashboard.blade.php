@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-100 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Banner with Background Image -->
        <div class="relative bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-xl overflow-hidden mb-6">
            <div class="absolute inset-0 bg-black opacity-50"></div>
            <div class="relative p-8">
                <h2 class="text-3xl font-bold text-white mb-2">Welcome back, {{ auth()->user()->name }}!</h2>
                <p class="text-white text-opacity-90">Here's your platform overview for {{ now()->format('F Y') }}</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Users Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                            <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalUsers ?? 0) }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">Total Users</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center text-sm {{ $monthlyStats['userGrowth'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $monthlyStats['userGrowth'] >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6' }}"></path>
                            </svg>
                            <span>{{ abs($monthlyStats['userGrowth']) }}% {{ $monthlyStats['userGrowth'] >= 0 ? 'increase' : 'decrease' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Jobs Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                            <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($activeJobs ?? 0) }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">Active Jobs</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center text-sm {{ $monthlyStats['jobGrowth'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $monthlyStats['jobGrowth'] >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6' }}"></path>
                            </svg>
                            <span>{{ abs($monthlyStats['jobGrowth']) }}% {{ $monthlyStats['jobGrowth'] >= 0 ? 'increase' : 'decrease' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Earnings Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                            <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">${{ number_format($totalEarnings ?? 0, 2) }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">Monthly Earnings</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center text-sm {{ $monthlyStats['earningsGrowth'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $monthlyStats['earningsGrowth'] >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6' }}"></path>
                            </svg>
                            <span>{{ abs($monthlyStats['earningsGrowth']) }}% {{ $monthlyStats['earningsGrowth'] >= 0 ? 'increase' : 'decrease' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Assessments Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10">
                            <svg class="h-8 w-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($pendingAssessments ?? 0) }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">Pending Assessments</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center text-sm text-yellow-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span>Needs attention</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('admin.jobs.create') }}" class="group relative inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity"></div>
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="font-semibold">Post New Job</span>
                    </a>
                    <a href="{{ route('admin.skill-assessments.create') }}" class="group relative inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity"></div>
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="font-semibold">Create Assessment</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="group relative inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-yellow-600 to-yellow-700 text-white rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity"></div>
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="font-semibold">Manage Users</span>
                    </a>
                    <a href="{{ route('admin.payments') }}" class="group relative inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity"></div>
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-semibold">Monthly Payments</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Jobs -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Recent Jobs</h3>
                        <a href="{{ route('admin.jobs.index') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">View all</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentJobs ?? [] as $job)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg transform transition-all duration-300 hover:scale-102">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $job->title }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Posted {{ $job->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $job->status === 'open' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100' }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </div>
                        @empty
                        <p class="text-gray-600 dark:text-gray-400 text-center py-4">No recent jobs found.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Assessments -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Recent Assessments</h3>
                        <a href="{{ route('admin.skill-assessments.index') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">View all</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentAssessments ?? [] as $assessment)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg transform transition-all duration-300 hover:scale-102">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $assessment->title }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Created {{ $assessment->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $assessment->status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' }}">
                                {{ ucfirst($assessment->status) }}
                            </span>
                        </div>
                        @empty
                        <p class="text-gray-600 dark:text-gray-400 text-center py-4">No recent assessments found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
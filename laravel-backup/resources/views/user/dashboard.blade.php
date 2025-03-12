@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <x-sidebar.user-sidebar />

    <div class="lg:pl-64">
        <main class="py-10">
            <div class="px-4 sm:px-6 lg:px-8">
                <!-- Dashboard Header -->
                <div class="mb-8">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Dashboard</h1>
                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                        Welcome back, {{ Auth::user()->name }}!
                    </p>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Recommended Jobs -->
                    <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                            Recommended Jobs
                                        </dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                                {{ $recommendedJobs->count() }}
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
                            <div class="text-sm">
                                <a href="{{ route('jobs.recommended') }}" class="font-medium text-primary-600 hover:text-primary-500">
                                    View all
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Active Bids -->
                    <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                            Active Bids
                                        </dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                                {{ $recentBids->count() }}
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
                            <div class="text-sm">
                                <a href="{{ route('bids.index') }}" class="font-medium text-primary-600 hover:text-primary-500">
                                    View all
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Unread Messages -->
                    <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                            Unread Messages
                                        </dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                                {{ $unreadMessages }}
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
                            <div class="text-sm">
                                <a href="{{ route('messages.index') }}" class="font-medium text-primary-600 hover:text-primary-500">
                                    View all
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Items -->
                    <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                            Cart Items
                                        </dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                                {{ $cartItemsCount }}
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
                            <div class="text-sm">
                                <a href="{{ route('cart.index') }}" class="font-medium text-primary-600 hover:text-primary-500">
                                    View cart
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Jobs -->
                <div class="mt-8">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Recommended Jobs</h2>
                    <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($recommendedJobs as $job)
                        <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="w-0 flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white truncate">
                                            {{ $job->title }}
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($job->description, 100) }}
                                        </p>
                                        <div class="mt-4">
                                            <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-800 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-100">
                                                ${{ number_format($job->budget) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
                                <div class="text-sm">
                                    <a href="{{ route('jobs.show', $job) }}" class="font-medium text-primary-600 hover:text-primary-500">
                                        View details
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Bids -->
                <div class="mt-8">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Recent Bids</h2>
                    <div class="mt-4 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">Job</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Amount</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                @foreach($recentBids as $bid)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $bid->job->title }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">${{ number_format($bid->amount) }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $bid->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($bid->status === 'accepted' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($bid->status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $bid->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection 
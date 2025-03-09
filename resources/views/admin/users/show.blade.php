<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('User Details') }}
            </h2>
            <div class="flex space-x-4">
                @if(!$user->is_verified)
                    <form method="POST" action="{{ route('admin.users.verify', $user) }}">
                        @csrf
                        <x-primary-button type="submit">
                            {{ __('Verify User') }}
                        </x-primary-button>
                    </form>
                @endif
                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                    @csrf
                    <x-secondary-button type="submit">
                        {{ $user->is_active ? __('Deactivate User') : __('Activate User') }}
                    </x-secondary-button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- User Profile -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-20 w-20">
                                @if($user->avatar)
                                    <img class="h-20 w-20 rounded-full" src="{{ $user->avatar }}" alt="{{ $user->name }}">
                                @else
                                    <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-xl">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-6">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                <div class="mt-2 flex space-x-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $user->role === 'client' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $user->role === 'freelancer' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $user->is_verified ? 'Verified' : 'Unverified' }}
                                    </span>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <dl class="grid grid-cols-1 gap-4">
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Joined</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Jobs</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                        {{ number_format($stats['total_jobs']) }}
                                    </dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Earnings</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                        ${{ number_format($stats['total_earnings'], 2) }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Activity Stats -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg md:col-span-2">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Activity Overview</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Jobs</dt>
                                <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                                    {{ number_format($stats['active_jobs']) }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Bids</dt>
                                <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                                    {{ number_format($stats['total_bids']) }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Accepted Bids</dt>
                                <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                                    {{ number_format($stats['accepted_bids']) }}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Jobs -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Jobs</h3>
                        <div class="space-y-4">
                            @foreach($user->jobs()->latest()->take(5)->get() as $job)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $job->title }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $job->created_at->diffForHumans() }}
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
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Bids</h3>
                        <div class="space-y-4">
                            @foreach($user->bids()->with('job')->latest()->take(5)->get() as $bid)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $bid->job->title }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $bid->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                ${{ number_format($bid->amount, 2) }}
                                            </span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ ucfirst($bid->status) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Payments</h3>
                        <div class="space-y-4">
                            @foreach($user->payments()->with('job')->latest()->take(5)->get() as $payment)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $payment->job->title }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $payment->created_at->format('M d, Y') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $payment->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                                ${{ number_format($payment->amount, 2) }}
                                            </span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ ucfirst($payment->status) }}
                                            </p>
                                        </div>
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
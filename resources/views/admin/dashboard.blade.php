<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm font-medium">Total Users</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ number_format($stats['total_users']) }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm font-medium">Total Jobs</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ number_format($stats['total_jobs']) }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm font-medium">Total Payments</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">${{ number_format($stats['total_payments'], 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Users -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
                            <a href="{{ route('admin.users') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">View All</a>
                        </div>
                        <div class="space-y-4">
                            @foreach($stats['recent_users'] as $user)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                        $user->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                                    }}">
                                        {{ $user->is_verified ? 'Verified' : 'Pending' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Recent Jobs -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Jobs</h3>
                            <a href="{{ route('admin.jobs') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">View All</a>
                        </div>
                        <div class="space-y-4">
                            @foreach($stats['recent_jobs'] as $job)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $job->title }}</div>
                                        <div class="text-sm text-gray-500">by {{ $job->client->name }}</div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                        $job->status === 'open' ? 'bg-green-100 text-green-800' :
                                        ($job->status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                                        'bg-gray-100 text-gray-800')
                                    }}">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-2">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Payments</h3>
                            <a href="{{ route('admin.payments') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">View All</a>
                        </div>
                        <div class="space-y-4">
                            @foreach($stats['recent_payments'] as $payment)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="font-medium text-gray-900">${{ number_format($payment->amount, 2) }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ $payment->user->name }} - {{ $payment->milestone->title }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                            $payment->status === 'completed' ? 'bg-green-100 text-green-800' :
                                            ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                            'bg-red-100 text-red-800')
                                        }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                        <div class="text-sm text-gray-500">{{ $payment->created_at->diffForHumans() }}</div>
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
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Job Details') }}
            </h2>
            <form method="POST" action="{{ route('admin.jobs.delete', $job) }}" onsubmit="return confirm('Are you sure you want to delete this job?');">
                @csrf
                @method('DELETE')
                <x-danger-button type="submit">
                    {{ __('Delete Job') }}
                </x-danger-button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Job Details -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $job->title }}</h3>
                        
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($job->user->avatar)
                                    <img class="h-10 w-10 rounded-full" src="{{ $job->user->avatar }}" alt="{{ $job->user->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-sm">{{ strtoupper(substr($job->user->name, 0, 2)) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $job->user->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Posted {{ $job->created_at->diffForHumans() }}</div>
                            </div>
                        </div>

                        <div class="prose dark:prose-invert max-w-none">
                            {!! nl2br(e($job->description)) !!}
                        </div>

                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <dl class="grid grid-cols-1 gap-4">
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Budget</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                        ${{ number_format($job->budget, 2) }}
                                    </dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $job->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $job->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $job->status === 'completed' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $job->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deadline</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                        {{ $job->deadline->format('M d, Y') }}
                                    </dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Required Skills</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($job->skills as $skill)
                                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700">
                                                    {{ $skill }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Bids -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg md:col-span-2">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Bids</h3>
                        <div class="space-y-4">
                            @forelse($job->bids as $bid)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($bid->user->avatar)
                                                    <img class="h-10 w-10 rounded-full" src="{{ $bid->user->avatar }}" alt="{{ $bid->user->name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                        <span class="text-gray-500 text-sm">{{ strtoupper(substr($bid->user->name, 0, 2)) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $bid->user->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    Delivery: {{ $bid->delivery_time }} days • {{ $bid->created_at->diffForHumans() }}
                                                </div>
                                            </div>
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
                                    <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $bid->proposal }}
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">No bids yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Milestones -->
                @if($job->milestones->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg md:col-span-3">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Milestones</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Due Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($job->milestones as $milestone)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $milestone->title }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $milestone->description }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">${{ number_format($milestone->amount, 2) }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $milestone->due_date->format('M d, Y') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $milestone->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                                        {{ $milestone->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                                        {{ $milestone->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                                        {{ ucfirst($milestone->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jobs Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Total Jobs</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_jobs']) }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ number_format($stats['active_jobs']) }} active jobs
                        </p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Bidding Stats</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_bids']) }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            Avg. {{ $stats['avg_bids_per_job'] }} bids per job
                        </p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Average Job Value</h3>
                        <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['avg_job_value'], 2) }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ number_format($stats['completed_jobs']) }} completed jobs
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jobs by Status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Jobs by Status</h3>
                        <div class="h-64" 
                             data-jobs="{{ json_encode([
                                 'labels' => $jobs_by_status->pluck('status')->map(fn($status) => ucfirst(str_replace('_', ' ', $status))),
                                 'counts' => $jobs_by_status->pluck('count')
                             ]) }}">
                            <canvas id="jobStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                        <div class="space-y-4">
                            @foreach($recent_activity as $job)
                                <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $job->title }}</h4>
                                            <p class="text-sm text-gray-500">
                                                Posted by {{ $job->user->name }} • {{ $job->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                ${{ number_format($job->budget, 2) }}
                                            </span>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $job->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $job->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $job->status === 'completed' ? 'bg-purple-100 text-purple-800' : '' }}
                                                {{ $job->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                                            </span>
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

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Jobs by Status Chart
        const statusCtx = document.getElementById('jobStatusChart').getContext('2d');
        const statusContainer = statusCtx.canvas.parentElement;
        const statusData = JSON.parse(statusContainer.dataset.jobs);

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusData.labels,
                datasets: [{
                    data: statusData.counts,
                    backgroundColor: ['#10B981', '#4F46E5', '#8B5CF6', '#EF4444']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout> 
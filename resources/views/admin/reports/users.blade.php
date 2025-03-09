<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Total Users</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($stats['total_users']) }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ number_format($stats['verified_users']) }} verified ({{ round(($stats['verified_users'] / max($stats['total_users'], 1)) * 100) }}%)
                        </p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">User Types</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Clients</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($stats['clients']) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Freelancers</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($stats['freelancers']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Recent Activity</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($stats['recent_signups']) }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">New users in the last 30 days</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Registration Trend -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Registration Trend</h3>
                        <div class="h-64" 
                             data-registrations="{{ json_encode([
                                 'months' => $registrations->pluck('month'),
                                 'counts' => $registrations->pluck('count')
                             ]) }}">
                            <canvas id="registrationChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- User Distribution -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">User Distribution</h3>
                        <div class="h-64" 
                             data-distribution="{{ json_encode([
                                 'clients' => $stats['clients'],
                                 'freelancers' => $stats['freelancers']
                             ]) }}">
                            <canvas id="distributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Registration Trend Chart
        const registrationCtx = document.getElementById('registrationChart').getContext('2d');
        const registrationContainer = registrationCtx.canvas.parentElement;
        const registrationData = JSON.parse(registrationContainer.dataset.registrations);

        new Chart(registrationCtx, {
            type: 'line',
            data: {
                labels: registrationData.months,
                datasets: [{
                    label: 'New Registrations',
                    data: registrationData.counts,
                    borderColor: '#4F46E5',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // User Distribution Chart
        const distributionCtx = document.getElementById('distributionChart').getContext('2d');
        const distributionContainer = distributionCtx.canvas.parentElement;
        const distributionData = JSON.parse(distributionContainer.dataset.distribution);

        new Chart(distributionCtx, {
            type: 'pie',
            data: {
                labels: ['Clients', 'Freelancers'],
                datasets: [{
                    data: [distributionData.clients, distributionData.freelancers],
                    backgroundColor: ['#4F46E5', '#10B981']
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
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Earnings Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Period Selection -->
            <div class="mb-6">
                <form method="GET" action="{{ route('admin.reports.earnings') }}" class="flex gap-4">
                    <select name="period" class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="week" {{ request('period') === 'week' ? 'selected' : '' }}>Last Week</option>
                        <option value="month" {{ request('period', 'month') === 'month' ? 'selected' : '' }}>Last Month</option>
                        <option value="year" {{ request('period') === 'year' ? 'selected' : '' }}>Last Year</option>
                    </select>
                    <x-primary-button type="submit">Update</x-primary-button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Earnings Chart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Earnings Over Time</h3>
                        <div class="h-64" 
                             data-earnings="{{ json_encode([
                                 'dates' => $earnings->pluck('date'),
                                 'totals' => $earnings->pluck('total')
                             ]) }}">
                            <canvas id="earningsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Earnings Summary -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Summary</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">Total Earnings</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    ${{ number_format($earnings->sum('total'), 2) }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">Average Daily</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    ${{ number_format($earnings->avg('total'), 2) }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">Highest Day</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    ${{ number_format($earnings->max('total'), 2) }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-2">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Detailed Earnings</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Earnings</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($earnings as $earning)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $earning->date }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ${{ number_format($earning->total, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('earningsChart').getContext('2d');
        const chartContainer = ctx.canvas.parentElement;
        const chartData = JSON.parse(chartContainer.dataset.earnings);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.dates,
                datasets: [{
                    label: 'Earnings',
                    data: chartData.totals,
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
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout> 
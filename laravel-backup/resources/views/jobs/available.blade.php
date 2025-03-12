@inject('pixabay', 'App\Services\PixabayService')

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Latest Orders') }}
            </h2>
            <div class="text-sm text-gray-600">
                Professional Writers: 195,427 | Completed Orders: 2,844,076 | Current Online Jobs: 1,247
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">
                        Available opportunities for our writers
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Topic/Title
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Deadline
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pages
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Salary
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($jobs as $job)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                            <a href="{{ route('jobs.show', $job) }}">{{ $job->title }}</a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $job->deadline->diffForHumans(['parts' => 2]) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ ceil($job->budget_min / 15) }} {{-- Assuming $15 per page --}}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-green-600 font-medium">
                                            ${{ number_format($job->budget_min, 2) }}
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
                        <div>
                            Current orders: {{ $jobs->count() }} | Total fees offered: ${{ number_format($jobs->sum('budget_min'), 2) }}
                        </div>
                        <a href="{{ route('jobs.index') }}" class="text-blue-600 hover:text-blue-800">
                            View All Orders →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
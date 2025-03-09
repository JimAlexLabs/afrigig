@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Skill Assessments</h2>
                        <a href="{{ route('admin.skill-assessments.create') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create New Assessment
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Title
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Skill
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Difficulty
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Attempts
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Pass Rate
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assessments as $assessment)
                                    <tr>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <div class="flex items-center">
                                                <div class="ml-3">
                                                    <p class="text-gray-900 whitespace-no-wrap">
                                                        {{ $assessment->title }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                {{ $assessment->skill->name }}
                                            </p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <span class="relative inline-block px-3 py-1 font-semibold 
                                                {{ $assessment->difficulty === 'beginner' ? 'text-green-900' : '' }}
                                                {{ $assessment->difficulty === 'intermediate' ? 'text-blue-900' : '' }}
                                                {{ $assessment->difficulty === 'advanced' ? 'text-yellow-900' : '' }}
                                                {{ $assessment->difficulty === 'expert' ? 'text-red-900' : '' }}
                                                leading-tight">
                                                <span aria-hidden class="absolute inset-0 
                                                    {{ $assessment->difficulty === 'beginner' ? 'bg-green-200' : '' }}
                                                    {{ $assessment->difficulty === 'intermediate' ? 'bg-blue-200' : '' }}
                                                    {{ $assessment->difficulty === 'advanced' ? 'bg-yellow-200' : '' }}
                                                    {{ $assessment->difficulty === 'expert' ? 'bg-red-200' : '' }}
                                                    opacity-50 rounded-full"></span>
                                                <span class="relative capitalize">{{ $assessment->difficulty }}</span>
                                            </span>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                {{ $assessment->attempts_count }}
                                            </p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                @if($assessment->attempts_count > 0)
                                                    {{ round(($assessment->passed_count / $assessment->attempts_count) * 100) }}%
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <span class="relative inline-block px-3 py-1 font-semibold 
                                                {{ $assessment->is_active ? 'text-green-900' : 'text-red-900' }} leading-tight">
                                                <span aria-hidden class="absolute inset-0 
                                                    {{ $assessment->is_active ? 'bg-green-200' : 'bg-red-200' }} 
                                                    opacity-50 rounded-full"></span>
                                                <span class="relative">{{ $assessment->is_active ? 'Active' : 'Inactive' }}</span>
                                            </span>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.skill-assessments.edit', $assessment) }}" 
                                                   class="text-blue-600 hover:text-blue-900">Edit</a>
                                                <form action="{{ route('admin.skill-assessments.delete', $assessment) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to delete this assessment?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                            No skill assessments found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $assessments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 
@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Provide Assessment Feedback</h2>
                        <a href="{{ route('admin.skill-assessments.results') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Results
                        </a>
                    </div>

                    <div class="mb-8 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-lg font-medium mb-4">Assessment Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">User</p>
                                <p class="font-medium">{{ $attempt->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Assessment</p>
                                <p class="font-medium">{{ $attempt->assessment->title }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Skill</p>
                                <p class="font-medium">{{ $attempt->assessment->skill->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Score</p>
                                <p class="font-medium">{{ $attempt->score }}%</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                                <p class="font-medium">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $attempt->passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $attempt->passed ? 'Passed' : 'Failed' }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Completed At</p>
                                <p class="font-medium">{{ $attempt->completed_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.skill-assessments.feedback.store', $attempt) }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="feedback" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Detailed Feedback</label>
                            <textarea name="feedback" id="feedback" rows="4" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Provide detailed feedback about the assessment performance...">{{ old('feedback') }}</textarea>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Areas for Improvement</label>
                            <div id="improvement-areas" class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="improvement_areas[]" required
                                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="Enter an area for improvement">
                                    <button type="button" onclick="this.closest('.flex').remove()"
                                            class="text-red-600 hover:text-red-900">Remove</button>
                                </div>
                            </div>
                            <button type="button" onclick="addImprovementArea()"
                                    class="text-sm text-blue-600 hover:text-blue-900">+ Add Improvement Area</button>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Recommended Resources</label>
                            <div id="resources" class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="recommended_resources[]" required
                                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="Enter a recommended resource (URL, book, course, etc.)">
                                    <button type="button" onclick="this.closest('.flex').remove()"
                                            class="text-red-600 hover:text-red-900">Remove</button>
                                </div>
                            </div>
                            <button type="button" onclick="addResource()"
                                    class="text-sm text-blue-600 hover:text-blue-900">+ Add Resource</button>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Submit Feedback
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function addImprovementArea() {
            const container = document.getElementById('improvement-areas');
            const div = document.createElement('div');
            div.className = 'flex items-center space-x-2';
            div.innerHTML = `
                <input type="text" name="improvement_areas[]" required
                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Enter an area for improvement">
                <button type="button" onclick="this.closest('.flex').remove()"
                        class="text-red-600 hover:text-red-900">Remove</button>
            `;
            container.appendChild(div);
        }

        function addResource() {
            const container = document.getElementById('resources');
            const div = document.createElement('div');
            div.className = 'flex items-center space-x-2';
            div.innerHTML = `
                <input type="text" name="recommended_resources[]" required
                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Enter a recommended resource (URL, book, course, etc.)">
                <button type="button" onclick="this.closest('.flex').remove()"
                        class="text-red-600 hover:text-red-900">Remove</button>
            `;
            container.appendChild(div);
        }
    </script>
    @endpush
@endsection 
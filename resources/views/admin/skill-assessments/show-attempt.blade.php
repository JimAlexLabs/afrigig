@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Assessment Attempt Details</h2>
                        <a href="{{ route('admin.skill-assessments.results') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Results
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- User Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">User Information</h3>
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 w-12 h-12">
                                    <img class="w-full h-full rounded-full"
                                         src="{{ $attempt->user->profile_photo_url }}"
                                         alt="{{ $attempt->user->name }}">
                                </div>
                                <div class="ml-4">
                                    <p class="text-lg font-medium">{{ $attempt->user->name }}</p>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $attempt->user->email }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Role</p>
                                    <p class="font-medium">{{ ucfirst($attempt->user->role) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Member Since</p>
                                    <p class="font-medium">{{ $attempt->user->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Assessment Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Assessment Information</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Assessment</p>
                                    <p class="font-medium">{{ $attempt->assessment->title }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Skill</p>
                                    <p class="font-medium">{{ $attempt->assessment->skill->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Difficulty</p>
                                    <p class="font-medium capitalize">{{ $attempt->assessment->difficulty }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Time Limit</p>
                                    <p class="font-medium">{{ $attempt->assessment->time_limit }} minutes</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attempt Results -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-8">
                        <h3 class="text-lg font-medium mb-4">Attempt Results</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Score</p>
                                <p class="text-3xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $attempt->score }}%
                                </p>
                            </div>
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                                <p class="text-3xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $attempt->passed ? 'Passed' : 'Failed' }}
                                </p>
                            </div>
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Time Taken</p>
                                <p class="text-3xl font-bold">
                                    {{ $attempt->time_taken }} min
                                </p>
                            </div>
                        </div>

                        <!-- Question Analysis -->
                        <div class="space-y-6">
                            <h4 class="text-md font-medium">Question Analysis</h4>
                            @foreach($attempt->answers as $index => $answer)
                                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h5 class="font-medium">Question {{ $index + 1 }}</h5>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $answer['category'] }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $answer['correct'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $answer['correct'] ? 'Correct' : 'Incorrect' }}
                                        </span>
                                    </div>
                                    <p class="mb-4">{{ $answer['question'] }}</p>
                                    <div class="space-y-2">
                                        @foreach($answer['options'] as $optionIndex => $option)
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs
                                                    {{ $optionIndex === $answer['selected_answer'] ? 
                                                        ($answer['correct'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') : 
                                                        ($optionIndex === $answer['correct_answer'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ chr(65 + $optionIndex) }}
                                                </span>
                                                <span class="{{ $optionIndex === $answer['selected_answer'] ? 'font-medium' : '' }}">
                                                    {{ $option }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if(!$answer['correct'])
                                        <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900 rounded">
                                            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                                <span class="font-medium">Explanation:</span> {{ $answer['explanation'] }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Feedback Section -->
                    @if($attempt->feedback)
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Feedback</h3>
                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-md font-medium mb-2">Detailed Feedback</h4>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $attempt->feedback->feedback }}</p>
                                </div>
                                <div>
                                    <h4 class="text-md font-medium mb-2">Areas for Improvement</h4>
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($attempt->feedback->improvement_areas as $area)
                                            <li class="text-gray-700 dark:text-gray-300">{{ $area }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="text-md font-medium mb-2">Recommended Resources</h4>
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($attempt->feedback->recommended_resources as $resource)
                                            <li class="text-gray-700 dark:text-gray-300">{{ $resource }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    Feedback provided by {{ $attempt->feedback->reviewer->name }} on {{ $attempt->feedback->feedback_date->format('M d, Y H:i') }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex justify-end">
                            <a href="{{ route('admin.skill-assessments.feedback.create', $attempt) }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Provide Feedback
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection 
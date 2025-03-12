@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Edit Skill Assessment</h2>
                        <a href="{{ route('admin.skill-assessments.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to List
                        </a>
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

                    <form action="{{ route('admin.skill-assessments.update', $assessment) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="skill_id" class="block text-sm font-medium text-gray-700">Skill</label>
                            <select id="skill_id" name="skill_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select a skill</option>
                                @foreach($skills as $skill)
                                    <option value="{{ $skill->id }}" 
                                            {{ old('skill_id', $assessment->skill_id) == $skill->id ? 'selected' : '' }}>
                                        {{ $skill->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" required
                                   value="{{ old('title', $assessment->title) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $assessment->description) }}</textarea>
                        </div>

                        <div>
                            <label for="difficulty" class="block text-sm font-medium text-gray-700">Difficulty</label>
                            <select id="difficulty" name="difficulty" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="beginner" {{ old('difficulty', $assessment->difficulty) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('difficulty', $assessment->difficulty) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ old('difficulty', $assessment->difficulty) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                <option value="expert" {{ old('difficulty', $assessment->difficulty) == 'expert' ? 'selected' : '' }}>Expert</option>
                            </select>
                        </div>

                        <div>
                            <label for="time_limit" class="block text-sm font-medium text-gray-700">Time Limit (minutes)</label>
                            <input type="number" name="time_limit" id="time_limit" required
                                   value="{{ old('time_limit', $assessment->time_limit) }}" min="5" max="180"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="passing_score" class="block text-sm font-medium text-gray-700">Passing Score (%)</label>
                            <input type="number" name="passing_score" id="passing_score" required
                                   value="{{ old('passing_score', $assessment->passing_score) }}" min="0" max="100"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1"
                                       {{ old('is_active', $assessment->is_active) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>

                        <div class="space-y-4" id="questions-container">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium">Questions</h3>
                                <button type="button" onclick="addQuestion()"
                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Add Question
                                </button>
                            </div>
                            <div id="questions-list" class="space-y-6">
                                @foreach($assessment->questions as $index => $question)
                                    <div class="p-4 border rounded-md space-y-4">
                                        <div class="flex justify-between items-start">
                                            <h4 class="text-md font-medium">Question {{ $index + 1 }}</h4>
                                            <button type="button" onclick="this.closest('.p-4').remove()"
                                                    class="text-red-600 hover:text-red-900">Remove</button>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Question Text</label>
                                            <textarea name="questions[{{ $index }}][question]" rows="2" required
                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $question['question'] }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Category</label>
                                            <input type="text" name="questions[{{ $index }}][category]" required
                                                   value="{{ $question['category'] }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700">Options</label>
                                            <div class="space-y-2" id="options-{{ $index }}">
                                                @foreach($question['options'] as $optionIndex => $option)
                                                    <div class="flex items-center space-x-2">
                                                        <input type="radio" name="questions[{{ $index }}][correct_answer]"
                                                               value="{{ $optionIndex }}"
                                                               {{ $question['correct_answer'] == $optionIndex ? 'checked' : '' }}>
                                                        <input type="text" name="questions[{{ $index }}][options][]"
                                                               value="{{ $option }}" required
                                                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                        @if($optionIndex > 1)
                                                            <button type="button" onclick="this.closest('.flex').remove()"
                                                                    class="text-red-600 hover:text-red-900">Remove</button>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" onclick="addOption({{ $index }})"
                                                    class="text-sm text-blue-600 hover:text-blue-900">+ Add Option</button>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Explanation</label>
                                            <textarea name="questions[{{ $index }}][explanation]" rows="2" required
                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $question['explanation'] }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Assessment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let questionCount = {{ count($assessment->questions) }};

        function addQuestion() {
            const questionsList = document.getElementById('questions-list');
            const questionDiv = document.createElement('div');
            questionDiv.className = 'p-4 border rounded-md space-y-4';
            questionDiv.innerHTML = `
                <div class="flex justify-between items-start">
                    <h4 class="text-md font-medium">Question ${questionCount + 1}</h4>
                    <button type="button" onclick="this.closest('.p-4').remove()"
                            class="text-red-600 hover:text-red-900">Remove</button>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Question Text</label>
                    <textarea name="questions[${questionCount}][question]" rows="2" required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <input type="text" name="questions[${questionCount}][category]" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Options</label>
                    <div class="space-y-2" id="options-${questionCount}">
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="questions[${questionCount}][correct_answer]" value="0" required>
                            <input type="text" name="questions[${questionCount}][options][]" required
                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Option 1">
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="questions[${questionCount}][correct_answer]" value="1">
                            <input type="text" name="questions[${questionCount}][options][]" required
                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Option 2">
                        </div>
                    </div>
                    <button type="button" onclick="addOption(${questionCount})"
                            class="text-sm text-blue-600 hover:text-blue-900">+ Add Option</button>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Explanation</label>
                    <textarea name="questions[${questionCount}][explanation]" rows="2" required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="Explain why the correct answer is right..."></textarea>
                </div>
            `;
            questionsList.appendChild(questionDiv);
            questionCount++;
        }

        function addOption(questionIndex) {
            const optionsContainer = document.getElementById(`options-${questionIndex}`);
            const optionCount = optionsContainer.children.length;
            const optionDiv = document.createElement('div');
            optionDiv.className = 'flex items-center space-x-2';
            optionDiv.innerHTML = `
                <input type="radio" name="questions[${questionIndex}][correct_answer]" value="${optionCount}">
                <input type="text" name="questions[${questionIndex}][options][]" required
                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Option ${optionCount + 1}">
                <button type="button" onclick="this.closest('.flex').remove()"
                        class="text-red-600 hover:text-red-900">Remove</button>
            `;
            optionsContainer.appendChild(optionDiv);
        }
    </script>
    @endpush
@endsection 
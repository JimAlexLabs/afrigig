<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Job') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Edit Job</h2>
                        <a href="{{ route('jobs.show', $job) }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>
                    </div>

                    <form method="POST" action="{{ route('jobs.update', $job) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Job Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $job->title)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Job Description')" />
                            <textarea id="description" name="description" rows="6" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description', $job->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Category -->
                        <div>
                            <x-input-label for="category" :value="__('Category')" />
                            <select id="category" name="category" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category', $job->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category')" />
                        </div>

                        <!-- Skills Required -->
                        <div>
                            <x-input-label for="skills_required" :value="__('Required Skills')" />
                            <select id="skills_required" name="skills_required[]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" multiple required>
                                @foreach($skills as $skill)
                                    <option value="{{ $skill->id }}" {{ in_array($skill->id, old('skills_required', $job->skills->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $skill->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('skills_required')" />
                            <p class="mt-1 text-sm text-gray-500">Hold Ctrl (Windows) or Cmd (Mac) to select multiple skills</p>
                        </div>

                        <!-- Budget Range -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="budget_min" :value="__('Minimum Budget ($)')" />
                                <x-text-input id="budget_min" name="budget_min" type="number" step="0.01" class="mt-1 block w-full" :value="old('budget_min', $job->budget_min)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('budget_min')" />
                            </div>
                            <div>
                                <x-input-label for="budget_max" :value="__('Maximum Budget ($)')" />
                                <x-text-input id="budget_max" name="budget_max" type="number" step="0.01" class="mt-1 block w-full" :value="old('budget_max', $job->budget_max)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('budget_max')" />
                            </div>
                        </div>

                        <!-- Experience Level -->
                        <div>
                            <x-input-label for="experience_level" :value="__('Required Experience Level')" />
                            <select id="experience_level" name="experience_level" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select experience level</option>
                                <option value="entry" {{ old('experience_level', $job->experience_level) == 'entry' ? 'selected' : '' }}>Entry Level</option>
                                <option value="intermediate" {{ old('experience_level', $job->experience_level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="expert" {{ old('experience_level', $job->experience_level) == 'expert' ? 'selected' : '' }}>Expert</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('experience_level')" />
                        </div>

                        <!-- Project Length -->
                        <div>
                            <x-input-label for="project_length" :value="__('Project Length')" />
                            <select id="project_length" name="project_length" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select project length</option>
                                <option value="less_than_1_month" {{ old('project_length', $job->project_length) == 'less_than_1_month' ? 'selected' : '' }}>Less than 1 month</option>
                                <option value="1_to_3_months" {{ old('project_length', $job->project_length) == '1_to_3_months' ? 'selected' : '' }}>1 to 3 months</option>
                                <option value="3_to_6_months" {{ old('project_length', $job->project_length) == '3_to_6_months' ? 'selected' : '' }}>3 to 6 months</option>
                                <option value="more_than_6_months" {{ old('project_length', $job->project_length) == 'more_than_6_months' ? 'selected' : '' }}>More than 6 months</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('project_length')" />
                        </div>

                        <!-- Deadline -->
                        <div>
                            <x-input-label for="deadline" :value="__('Application Deadline')" />
                            <x-text-input id="deadline" name="deadline" type="date" class="mt-1 block w-full" :value="old('deadline', $job->deadline->format('Y-m-d'))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('deadline')" />
                        </div>

                        <!-- Current Attachments -->
                        @if($job->attachments)
                            <div>
                                <x-input-label :value="__('Current Attachments')" />
                                <div class="mt-2 space-y-2">
                                    @foreach(json_decode($job->attachments) as $key => $attachment)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center">
                                                <svg class="w-6 h-6 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                </svg>
                                                <a href="{{ Storage::url($attachment) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-900">
                                                    {{ basename($attachment) }}
                                                </a>
                                            </div>
                                            <div class="flex items-center">
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="remove_attachments[]" value="{{ $key }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-sm text-gray-600">Remove</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- New Attachments -->
                        <div>
                            <x-input-label for="attachments" :value="__('Add New Attachments')" />
                            <input id="attachments" name="attachments[]" type="file" multiple class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100" />
                            <x-input-error class="mt-2" :messages="$errors->get('attachments')" />
                            <p class="mt-1 text-sm text-gray-500">Upload any relevant files (max 5 files, 10MB each)</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Update Job') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
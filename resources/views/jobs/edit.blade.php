<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Job') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('jobs.update', $job) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Job Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $job->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Job Description')" />
                            <textarea id="description" name="description" rows="6" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('description', $job->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Category -->
                        <div>
                            <x-input-label for="category" :value="__('Category')" />
                            <select id="category" name="category" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select a category</option>
                                <option value="web" {{ old('category', $job->category) === 'web' ? 'selected' : '' }}>Web Development</option>
                                <option value="mobile" {{ old('category', $job->category) === 'mobile' ? 'selected' : '' }}>Mobile Development</option>
                                <option value="design" {{ old('category', $job->category) === 'design' ? 'selected' : '' }}>Design</option>
                                <option value="writing" {{ old('category', $job->category) === 'writing' ? 'selected' : '' }}>Writing</option>
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <!-- Skills Required -->
                        <div>
                            <x-input-label for="skills" :value="__('Required Skills (comma-separated)')" />
                            <x-text-input id="skills" class="block mt-1 w-full" type="text" name="skills_required" :value="old('skills_required', implode(', ', $job->skills_required))" required />
                            <x-input-error :messages="$errors->get('skills_required')" class="mt-2" />
                        </div>

                        <!-- Budget Range -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="budget_min" :value="__('Minimum Budget ($)')" />
                                <x-text-input id="budget_min" class="block mt-1 w-full" type="number" name="budget_min" :value="old('budget_min', $job->budget_min)" required min="0" step="0.01" />
                                <x-input-error :messages="$errors->get('budget_min')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="budget_max" :value="__('Maximum Budget ($)')" />
                                <x-text-input id="budget_max" class="block mt-1 w-full" type="number" name="budget_max" :value="old('budget_max', $job->budget_max)" required min="0" step="0.01" />
                                <x-input-error :messages="$errors->get('budget_max')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Deadline -->
                        <div>
                            <x-input-label for="deadline" :value="__('Deadline')" />
                            <x-text-input id="deadline" class="block mt-1 w-full" type="date" name="deadline" :value="old('deadline', $job->deadline->format('Y-m-d'))" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" />
                            <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
                        </div>

                        <!-- Experience Level -->
                        <div>
                            <x-input-label for="experience_level" :value="__('Required Experience Level')" />
                            <select id="experience_level" name="experience_level" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select experience level</option>
                                <option value="entry" {{ old('experience_level', $job->experience_level) === 'entry' ? 'selected' : '' }}>Entry Level</option>
                                <option value="intermediate" {{ old('experience_level', $job->experience_level) === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="expert" {{ old('experience_level', $job->experience_level) === 'expert' ? 'selected' : '' }}>Expert</option>
                            </select>
                            <x-input-error :messages="$errors->get('experience_level')" class="mt-2" />
                        </div>

                        <!-- Project Length -->
                        <div>
                            <x-input-label for="project_length" :value="__('Project Length')" />
                            <select id="project_length" name="project_length" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select project length</option>
                                <option value="short" {{ old('project_length', $job->project_length) === 'short' ? 'selected' : '' }}>Short Term (< 1 month)</option>
                                <option value="medium" {{ old('project_length', $job->project_length) === 'medium' ? 'selected' : '' }}>Medium Term (1-3 months)</option>
                                <option value="long" {{ old('project_length', $job->project_length) === 'long' ? 'selected' : '' }}>Long Term (3+ months)</option>
                            </select>
                            <x-input-error :messages="$errors->get('project_length')" class="mt-2" />
                        </div>

                        <!-- Current Attachments -->
                        @if($job->attachments)
                            <div>
                                <x-input-label :value="__('Current Attachments')" />
                                <ul class="mt-2 list-disc pl-5">
                                    @foreach($job->attachments as $attachment)
                                        <li class="text-sm text-gray-600">
                                            <a href="{{ Storage::url($attachment) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                                {{ basename($attachment) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- New Attachments -->
                        <div>
                            <x-input-label for="attachments" :value="__('Add New Attachments')" />
                            <input id="attachments" type="file" name="attachments[]" multiple class="block mt-1 w-full text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            <p class="mt-1 text-sm text-gray-500">Upload any new files (Max 10MB each)</p>
                            <x-input-error :messages="$errors->get('attachments')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Update Job') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Convert comma-separated skills input into an array
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            const skillsInput = document.getElementById('skills');
            const skills = skillsInput.value.split(',').map(skill => skill.trim()).filter(Boolean);
            skillsInput.value = JSON.stringify(skills);
            this.submit();
        });
    </script>
    @endpush
</x-app-layout> 
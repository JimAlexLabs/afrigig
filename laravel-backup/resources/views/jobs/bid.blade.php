<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Job
                        </a>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold mb-2">Submit a Bid</h2>
                        <p class="text-gray-600">You are bidding on: <span class="font-medium text-gray-900">{{ $job->title }}</span></p>
                    </div>

                    <!-- Job Summary -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-8">
                        <h3 class="text-lg font-semibold mb-4">Job Summary</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Budget Range</dt>
                                <dd class="mt-1 text-sm text-gray-900">${{ number_format($job->budget_min) }} - ${{ number_format($job->budget_max) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Experience Level</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($job->experience_level) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Project Length</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ str_replace('_', ' ', ucfirst($job->project_length)) }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Bid Form -->
                    <form method="POST" action="{{ route('jobs.bids.store', $job) }}" class="space-y-6">
                        @csrf

                        <!-- Bid Amount -->
                        <div>
                            <x-input-label for="amount" :value="__('Bid Amount ($)')" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <x-text-input id="amount" name="amount" type="number" step="0.01" class="pl-7 mt-1 block w-full" :value="old('amount')" required placeholder="Enter your bid amount" />
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Enter an amount between ${{ number_format($job->budget_min) }} and ${{ number_format($job->budget_max) }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('amount')" />
                        </div>

                        <!-- Delivery Time -->
                        <div>
                            <x-input-label for="delivery_time" :value="__('Delivery Time (Days)')" />
                            <x-text-input id="delivery_time" name="delivery_time" type="number" class="mt-1 block w-full" :value="old('delivery_time')" required min="1" placeholder="Enter estimated delivery time in days" />
                            <x-input-error class="mt-2" :messages="$errors->get('delivery_time')" />
                        </div>

                        <!-- Cover Letter -->
                        <div>
                            <x-input-label for="proposal" :value="__('Cover Letter')" />
                            <div class="mt-1">
                                <textarea id="proposal" name="proposal" rows="8" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Introduce yourself and explain why you're the best fit for this job...">{{ old('proposal') }}</textarea>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Minimum 100 characters. Include your relevant experience and approach to the project.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('proposal')" />
                        </div>

                        <!-- Milestones -->
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <x-input-label :value="__('Project Milestones')" />
                                <button type="button" onclick="addMilestone()" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add Milestone
                                </button>
                            </div>
                            <div id="milestones" class="space-y-4">
                                <div class="milestone-item bg-gray-50 p-4 rounded-lg">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="milestones[0][title]" :value="__('Title')" />
                                            <x-text-input id="milestones[0][title]" name="milestones[0][title]" type="text" class="mt-1 block w-full" required />
                                        </div>
                                        <div>
                                            <x-input-label for="milestones[0][amount]" :value="__('Amount ($)')" />
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">$</span>
                                                </div>
                                                <x-text-input id="milestones[0][amount]" name="milestones[0][amount]" type="number" step="0.01" class="pl-7 mt-1 block w-full" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <x-input-label for="milestones[0][description]" :value="__('Description')" />
                                        <textarea id="milestones[0][description]" name="milestones[0][description]" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Break down the project into specific deliverables with their respective costs.</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Submit Bid') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let milestoneCount = 1;

        function addMilestone() {
            const milestonesContainer = document.getElementById('milestones');
            const template = `
                <div class="milestone-item bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-end mb-2">
                        <button type="button" onclick="this.closest('.milestone-item').remove()" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="milestones[${milestoneCount}][title]" :value="__('Title')" />
                            <x-text-input id="milestones[${milestoneCount}][title]" name="milestones[${milestoneCount}][title]" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="milestones[${milestoneCount}][amount]" :value="__('Amount ($)')" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <x-text-input id="milestones[${milestoneCount}][amount]" name="milestones[${milestoneCount}][amount]" type="number" step="0.01" class="pl-7 mt-1 block w-full" required />
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <x-input-label for="milestones[${milestoneCount}][description]" :value="__('Description')" />
                        <textarea id="milestones[${milestoneCount}][description]" name="milestones[${milestoneCount}][description]" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required></textarea>
                    </div>
                </div>
            `;
            milestonesContainer.insertAdjacentHTML('beforeend', template);
            milestoneCount++;
        }
    </script>
    @endpush
</x-app-layout> 
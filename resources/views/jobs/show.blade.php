<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $job->title }}
            </h2>
            @if($job->client_id === auth()->id())
                <div class="flex space-x-4">
                    <a href="{{ route('jobs.edit', $job) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        {{ __('Edit Job') }}
                    </a>
                    <form method="POST" action="{{ route('jobs.destroy', $job) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this job?')">
                            {{ __('Delete Job') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Job Details -->
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="prose max-w-none">
                                <h3 class="text-lg font-semibold mb-4">Job Description</h3>
                                <p class="whitespace-pre-line">{{ $job->description }}</p>

                                <div class="mt-6">
                                    <h4 class="text-md font-semibold mb-2">Required Skills</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($job->skills_required as $skill)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ $skill }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                @if($job->attachments)
                                    <div class="mt-6">
                                        <h4 class="text-md font-semibold mb-2">Attachments</h4>
                                        <ul class="list-disc pl-5">
                                            @foreach($job->attachments as $attachment)
                                                <li>
                                                    <a href="{{ Storage::url($attachment) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                                        {{ basename($attachment) }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Bids Section -->
                    @if($job->client_id === auth()->id() || $job->bids->contains('freelancer_id', auth()->id()))
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-semibold mb-4">Bids ({{ $job->bids->count() }})</h3>
                                
                                @if($job->bids->isEmpty())
                                    <p class="text-gray-500">No bids yet.</p>
                                @else
                                    <div class="space-y-6">
                                        @foreach($job->bids as $bid)
                                            <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <h4 class="font-semibold">{{ $bid->freelancer->name }}</h4>
                                                        <p class="mt-2 text-sm text-gray-600">{{ $bid->proposal }}</p>
                                                        <div class="mt-2 text-sm text-gray-500">
                                                            <span>Delivery: {{ $bid->delivery_time }} days</span>
                                                            <span class="mx-2">•</span>
                                                            <span>Bid: ${{ number_format($bid->amount, 2) }}</span>
                                                        </div>
                                                    </div>
                                                    @if($job->client_id === auth()->id() && $job->status === 'open')
                                                        <form method="POST" action="{{ route('jobs.bids.accept', [$job, $bid]) }}">
                                                            @csrf
                                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                                                {{ __('Accept Bid') }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Job Details Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold mb-4">Job Details</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Budget</dt>
                                    <dd class="mt-1 text-sm text-gray-900">${{ number_format($job->budget_min) }} - ${{ number_format($job->budget_max) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($job->category) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Experience Level</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($job->experience_level) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Project Length</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($job->project_length) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Posted By</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $job->client->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Posted</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $job->created_at->diffForHumans() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Deadline</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $job->deadline->format('M d, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Submit Bid Card -->
                    @if(auth()->user()->role === 'freelancer' && $job->status === 'open' && !$job->bids->contains('freelancer_id', auth()->id()))
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-semibold mb-4">Submit a Bid</h3>
                                <form method="POST" action="{{ route('jobs.bids.store', $job) }}" class="space-y-4">
                                    @csrf

                                    <div>
                                        <x-input-label for="amount" :value="__('Bid Amount ($)')" />
                                        <x-text-input id="amount" class="block mt-1 w-full" type="number" name="amount" :value="old('amount')" required min="{{ $job->budget_min }}" max="{{ $job->budget_max }}" step="0.01" />
                                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="delivery_time" :value="__('Delivery Time (days)')" />
                                        <x-text-input id="delivery_time" class="block mt-1 w-full" type="number" name="delivery_time" :value="old('delivery_time')" required min="1" />
                                        <x-input-error :messages="$errors->get('delivery_time')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="proposal" :value="__('Cover Letter')" />
                                        <textarea id="proposal" name="proposal" rows="4" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required minlength="100">{{ old('proposal') }}</textarea>
                                        <x-input-error :messages="$errors->get('proposal')" class="mt-2" />
                                    </div>

                                    <div class="flex items-center justify-end">
                                        <x-primary-button>
                                            {{ __('Submit Bid') }}
                                        </x-primary-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
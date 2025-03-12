<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 flex justify-between items-center">
                        <div>
                            <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Job
                            </a>
                        </div>
                        <div class="text-right">
                            <h2 class="text-xl font-semibold">{{ $job->title }}</h2>
                            <p class="text-sm text-gray-600">Conversation with {{ $otherUser->name }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Job Status</h3>
                                <p class="text-sm text-gray-600">{{ ucfirst($job->status) }}</p>
                            </div>
                            @if($job->status === 'in_progress')
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Current Milestone</h3>
                                    @if($currentMilestone)
                                        <p class="text-sm text-gray-600">{{ $currentMilestone->title }}</p>
                                    @else
                                        <p class="text-sm text-gray-600">No active milestone</p>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Due Date</h3>
                                    @if($currentMilestone)
                                        <p class="text-sm text-gray-600">{{ $currentMilestone->due_date->format('M d, Y') }}</p>
                                    @else
                                        <p class="text-sm text-gray-600">-</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Messages Container -->
                    <div id="messages" class="space-y-4 mb-6 h-96 overflow-y-auto">
                        @foreach($messages as $message)
                            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="flex items-start max-w-xl {{ $message->sender_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                                    <div class="flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full" src="{{ $message->sender->profile_photo_url }}" alt="{{ $message->sender->name }}">
                                    </div>
                                    <div class="{{ $message->sender_id === auth()->id() ? 'mr-4' : 'ml-4' }}">
                                        <div class="{{ $message->sender_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-900' }} rounded-lg px-4 py-2 shadow-sm">
                                            @if($message->attachment)
                                                <div class="mb-2">
                                                    <a href="{{ Storage::url($message->attachment) }}" target="_blank" class="inline-flex items-center text-sm {{ $message->sender_id === auth()->id() ? 'text-indigo-100 hover:text-white' : 'text-indigo-600 hover:text-indigo-900' }}">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                        </svg>
                                                        {{ basename($message->attachment) }}
                                                    </a>
                                                </div>
                                            @endif
                                            <p class="text-sm whitespace-pre-wrap">{{ $message->content }}</p>
                                        </div>
                                        <div class="mt-1 text-xs {{ $message->sender_id === auth()->id() ? 'text-right' : '' }} text-gray-500">
                                            {{ $message->created_at->format('M d, g:i A') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Message Form -->
                    <form method="POST" action="{{ route('messages.store', ['job' => $job->id, 'user' => $otherUser->id]) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="flex items-start space-x-4">
                            <div class="flex-grow">
                                <textarea name="content" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Type your message..." required></textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('content')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <label for="attachment" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                        Attach File
                                    </label>
                                    <input id="attachment" name="attachment" type="file" class="hidden">
                                </div>
                                <div id="selected-file" class="text-sm text-gray-600"></div>
                            </div>
                            <x-primary-button>
                                Send Message
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Scroll to bottom of messages on load
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('messages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });

        // Show selected file name
        document.getElementById('attachment').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || '';
            document.getElementById('selected-file').textContent = fileName;
        });
    </script>
    @endpush
</x-app-layout> 
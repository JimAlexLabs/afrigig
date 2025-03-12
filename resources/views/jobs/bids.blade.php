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
                        <h2 class="text-2xl font-semibold mb-2">Bids for: {{ $job->title }}</h2>
                        <p class="text-gray-600">{{ $bids->count() }} bid(s) received</p>
                    </div>

                    @if($bids->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No bids yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Be the first to bid on this project.</p>
                            @if(auth()->user()->id !== $job->client_id)
                                <div class="mt-6">
                                    <a href="{{ route('jobs.bid', $job) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Submit a Bid
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($bids as $bid)
                                <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <img class="h-12 w-12 rounded-full" src="{{ $bid->freelancer->profile_photo_url }}" alt="{{ $bid->freelancer->name }}">
                                                </div>
                                                <div>
                                                    <h3 class="text-lg font-medium text-gray-900">{{ $bid->freelancer->name }}</h3>
                                                    <p class="text-sm text-gray-500">Bid submitted {{ $bid->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-2xl font-semibold text-gray-900">${{ number_format($bid->amount, 2) }}</p>
                                                <p class="text-sm text-gray-500">Delivery in {{ $bid->delivery_time }} days</p>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <h4 class="text-sm font-medium text-gray-900">Cover Letter</h4>
                                            <p class="mt-1 text-sm text-gray-600">{{ $bid->proposal }}</p>
                                        </div>

                                        @if($bid->milestones->isNotEmpty())
                                            <div class="mt-6">
                                                <h4 class="text-sm font-medium text-gray-900 mb-3">Proposed Milestones</h4>
                                                <div class="space-y-3">
                                                    @foreach($bid->milestones as $milestone)
                                                        <div class="bg-gray-50 p-3 rounded-md">
                                                            <div class="flex justify-between items-start">
                                                                <div>
                                                                    <h5 class="text-sm font-medium text-gray-900">{{ $milestone->title }}</h5>
                                                                    <p class="mt-1 text-sm text-gray-600">{{ $milestone->description }}</p>
                                                                </div>
                                                                <p class="text-sm font-medium text-gray-900">${{ number_format($milestone->amount, 2) }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <div class="mt-6 flex items-center justify-end space-x-4">
                                            @if(auth()->user()->id === $job->client_id && $job->status === 'open')
                                                <form method="POST" action="{{ route('jobs.bids.accept', [$job, $bid]) }}">
                                                    @csrf
                                                    <x-primary-button>
                                                        {{ __('Accept Bid') }}
                                                    </x-primary-button>
                                                </form>
                                            @endif

                                            @if(auth()->user()->id === $bid->freelancer_id && $job->status === 'open')
                                                <form method="POST" action="{{ route('jobs.bids.withdraw', [$job, $bid]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-danger-button>
                                                        {{ __('Withdraw Bid') }}
                                                    </x-danger-button>
                                                </form>
                                            @endif

                                            <a href="{{ route('messages.show', ['job' => $job->id, 'user' => $bid->freelancer_id]) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                                </svg>
                                                Message
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{ $bids->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
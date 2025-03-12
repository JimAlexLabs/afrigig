@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">My Jobs</h1>
    </div>

    @if($jobs->isEmpty())
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-600">You haven't placed any bids yet.</p>
            <a href="{{ route('jobs.available') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Browse Available Jobs
            </a>
        </div>
    @else
        <div class="grid gap-6">
            @foreach($jobs as $job)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">
                                    <a href="{{ route('jobs.show', $job) }}" class="hover:text-blue-600">
                                        {{ $job->title }}
                                    </a>
                                </h2>
                                <p class="mt-2 text-gray-600">{{ Str::limit($job->description, 200) }}</p>
                            </div>
                            <span class="px-3 py-1 text-sm rounded-full {{ $job->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">Budget:</span>
                                ${{ number_format($job->budget, 2) }}
                            </div>
                            <div>
                                <span class="font-medium">Timeline:</span>
                                {{ $job->timeline }}
                            </div>
                            <div>
                                <span class="font-medium">Posted by:</span>
                                {{ $job->user->name }}
                            </div>
                        </div>

                        <div class="mt-4 border-t pt-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Your Bid</h3>
                            @foreach($job->bids as $bid)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-gray-600">
                                            <span class="font-medium">Amount:</span>
                                            ${{ number_format($bid->amount, 2) }}
                                        </p>
                                        <p class="text-gray-600">
                                            <span class="font-medium">Status:</span>
                                            <span class="capitalize">{{ $bid->status }}</span>
                                        </p>
                                    </div>
                                    @if($bid->status === 'pending')
                                        <form action="{{ route('jobs.bid', $job) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                Withdraw Bid
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $jobs->links() }}
        </div>
    @endif
</div>
@endsection 
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4">
                    <div class="rounded-md bg-green-50 dark:bg-green-900 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4">
                    <div class="rounded-md bg-red-50 dark:bg-red-900 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800 dark:text-red-200">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Cart Items -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @forelse($cartItems as $item)
                        <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-8 last:border-b-0 last:pb-0">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $item->job->title }}</h3>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($item->job->description, 150) }}</p>
                                    
                                    <!-- Bid Details Form -->
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="mt-4 space-y-4">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Bid Amount -->
                                            <div>
                                                <label for="amount_{{ $item->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Bid Amount
                                                </label>
                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                                                    </div>
                                                    <input type="number" name="amount" id="amount_{{ $item->id }}" 
                                                        class="block w-full pl-7 pr-12 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md"
                                                        value="{{ $item->amount }}" required min="1" step="0.01">
                                                </div>
                                            </div>

                                            <!-- Timeline -->
                                            <div>
                                                <label for="timeline_{{ $item->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Timeline (in days)
                                                </label>
                                                <input type="number" name="timeline" id="timeline_{{ $item->id }}"
                                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm"
                                                    value="{{ $item->timeline }}" required min="1">
                                            </div>
                                        </div>

                                        <!-- Proposal -->
                                        <div>
                                            <label for="proposal_{{ $item->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Proposal Description
                                            </label>
                                            <textarea name="proposal" id="proposal_{{ $item->id }}" rows="3"
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm"
                                                required>{{ $item->proposal }}</textarea>
                                        </div>

                                        <!-- Premium Features -->
                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="hide_bid" id="hide_bid_{{ $item->id }}"
                                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                                                        {{ $item->hide_bid ? 'checked' : '' }}>
                                                    <label for="hide_bid_{{ $item->id }}" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                        Hide Bid from Other Freelancers
                                                    </label>
                                                </div>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    ${{ number_format($item->hide_bid_cost, 2) }}
                                                </span>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="feature_bid" id="feature_bid_{{ $item->id }}"
                                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                                                        {{ $item->feature_bid ? 'checked' : '' }}>
                                                    <label for="feature_bid_{{ $item->id }}" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                        Feature Bid (Highlight to Client)
                                                    </label>
                                                </div>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    ${{ number_format($item->feature_bid_cost, 2) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex justify-end space-x-3">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Update Bid
                                            </button>
                                            
                                            <form action="{{ route('cart.destroy', $item) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No items in cart</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by browsing available jobs.</p>
                            <div class="mt-6">
                                <a href="{{ route('jobs.available') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Browse Jobs
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Cart Summary -->
            @if($cartItems->isNotEmpty())
                <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Cart Summary</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Premium Features Cost:</span>
                                <span class="text-gray-900 dark:text-gray-100">${{ number_format($totalPremiumCost, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Your Balance:</span>
                                <span class="text-gray-900 dark:text-gray-100">${{ number_format(auth()->user()->balance, 2) }}</span>
                            </div>
                        </div>

                        @if($totalPremiumCost > auth()->user()->balance)
                            <div class="mt-4 rounded-md bg-yellow-50 dark:bg-yellow-900 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                            Insufficient Balance
                                        </h3>
                                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                            <p>
                                                You need to add funds to your account to use premium features.
                                                <a href="{{ route('wallet.deposit') }}" class="font-medium underline">Add Funds</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mt-6 flex justify-end">
                            <form action="{{ route('cart.submit') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                                    {{ $totalPremiumCost > auth()->user()->balance ? 'disabled' : '' }}>
                                    Submit All Bids
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Welcome Tour -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hasSeenTour = localStorage.getItem('hasSeenCartTour');
            
            if (!hasSeenTour) {
                const tour = new Shepherd.Tour({
                    defaultStepOptions: {
                        cancelIcon: {
                            enabled: true
                        },
                        classes: 'shadow-md bg-purple-50 dark:bg-gray-900',
                        scrollTo: true
                    }
                });

                tour.addStep({
                    title: 'Welcome to Your Cart',
                    text: 'Here you can manage your job bids and premium features.',
                    attachTo: {
                        element: '.max-w-7xl',
                        on: 'bottom'
                    },
                    buttons: [
                        {
                            text: 'Next',
                            action: tour.next
                        }
                    ]
                });

                tour.addStep({
                    title: 'Bid Management',
                    text: 'Set your bid amount, timeline, and write a compelling proposal.',
                    attachTo: {
                        element: 'form',
                        on: 'bottom'
                    },
                    buttons: [
                        {
                            text: 'Back',
                            action: tour.back
                        },
                        {
                            text: 'Next',
                            action: tour.next
                        }
                    ]
                });

                tour.addStep({
                    title: 'Premium Features',
                    text: 'Enhance your bid visibility with premium features like hiding your bid or featuring it to the client.',
                    attachTo: {
                        element: '.space-y-4',
                        on: 'bottom'
                    },
                    buttons: [
                        {
                            text: 'Back',
                            action: tour.back
                        },
                        {
                            text: 'Finish',
                            action: tour.complete
                        }
                    ]
                });

                tour.start();
                localStorage.setItem('hasSeenCartTour', 'true');
            }
        });
    </script>
    @endpush
</x-app-layout> 
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if($notifications->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No notifications</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You're all caught up!</p>
                        </div>
                    @else
                        <div class="flex justify-end mb-4">
                            <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900 hover:bg-blue-200 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Mark all as read
                                </button>
                            </form>
                        </div>

                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($notifications as $notification)
                                <div class="py-4 {{ $notification->read_at ? 'opacity-75' : '' }}">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($notification->data['type'] === 'bid_status')
                                                <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $notification->data['title'] }}
                                                </h4>
                                                <div class="flex items-center space-x-4">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </span>
                                                    @unless($notification->read_at)
                                                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                                Mark as read
                                                            </button>
                                                        </form>
                                                    @endunless
                                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <p class="mt-1 text-gray-600 dark:text-gray-400">
                                                {{ $notification->data['message'] }}
                                            </p>
                                            @if(isset($notification->data['action_url']))
                                                <div class="mt-2">
                                                    <a href="{{ $notification->data['action_url'] }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                        View details →
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
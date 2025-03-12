<div x-data="{ open: false, notifications: [] }" @click.away="open = false" class="relative">
    <button @click="open = !open; if (open) { fetch('/notifications').then(r => r.json()).then(data => notifications = data) }" 
        class="relative inline-flex items-center p-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        <span x-show="unreadCount > 0" class="absolute top-0 right-0 -mt-1 -mr-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
            <span x-text="unreadCount"></span>
        </span>
    </button>

    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        role="menu" aria-orientation="vertical" aria-labelledby="notifications-menu">
        <div class="py-1" role="none">
            <div class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold">Notifications</h3>
                    <button @click="fetch('/notifications/mark-all-read', { method: 'POST' }).then(() => unreadCount = 0)"
                        class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        Mark all as read
                    </button>
                </div>
            </div>

            <template x-if="notifications.length === 0">
                <div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                    No notifications
                </div>
            </template>

            <template x-for="notification in notifications" :key="notification.id">
                <a :href="notification.data.action_url" class="block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700"
                    :class="{ 'bg-blue-50 dark:bg-blue-900/20': !notification.read_at }">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <template x-if="notification.data.type === 'bid_status'">
                                <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </template>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="notification.data.title"></p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-text="notification.data.message"></p>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500" x-text="new Date(notification.created_at).toLocaleDateString()"></p>
                        </div>
                    </div>
                </a>
            </template>

            <div class="border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-sm text-center text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                    View all notifications
                </a>
            </div>
        </div>
    </div>
</div> 
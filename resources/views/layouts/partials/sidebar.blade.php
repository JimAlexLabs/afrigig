<!-- Sidebar backdrop -->
<div x-show="sidebarOpen" class="fixed inset-0 z-20 transition-opacity bg-black bg-opacity-50 lg:hidden" @click="sidebarOpen = false" x-cloak></div>

<!-- Sidebar -->
<div x-show="sidebarOpen" class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-white lg:translate-x-0 lg:static lg:inset-0" x-cloak>
    <div class="flex items-center justify-center mt-8">
        <div class="flex items-center">
            <span class="mx-2 text-2xl font-semibold text-gray-800">Afrigig</span>
        </div>
    </div>

    <nav class="mt-10">
        <!-- Dashboard -->
        <a class="flex items-center px-6 py-2 mt-4 {{ request()->routeIs('dashboard') ? 'text-gray-100 bg-blue-600' : 'text-gray-500 hover:bg-gray-100' }}"
           href="{{ route('dashboard') }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
            </svg>
            <span class="mx-3">Dashboard</span>
        </a>

        <!-- My Cart -->
        <a class="flex items-center px-6 py-2 mt-4 {{ request()->routeIs('cart.*') ? 'text-gray-100 bg-blue-600' : 'text-gray-500 hover:bg-gray-100' }}"
           href="{{ route('cart.index') }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span class="mx-3">My Cart</span>
            @if(auth()->user()->cart && auth()->user()->cart->count() > 0)
                <span class="px-2 py-0.5 ml-auto text-xs font-medium tracking-wide text-blue-500 bg-blue-100 rounded-full">{{ auth()->user()->cart->count() }}</span>
            @endif
        </a>

        <!-- Notifications -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center w-full px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span class="mx-3">Notifications</span>
                @if(auth()->user()->unreadNotifications && auth()->user()->unreadNotifications->count() > 0)
                    <span class="px-2 py-0.5 ml-auto text-xs font-medium tracking-wide text-red-500 bg-red-100 rounded-full">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </button>
            
            <div x-show="open" @click.away="open = false" class="absolute left-0 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg" x-cloak>
                <div class="py-1">
                    @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            {{ $notification->data['message'] }}
                        </a>
                    @empty
                        <p class="px-4 py-2 text-sm text-gray-700">No new notifications</p>
                    @endforelse
                    @if(auth()->user()->unreadNotifications->count() > 5)
                        <a href="#" class="block px-4 py-2 text-sm text-blue-600 hover:bg-gray-100">
                            View all notifications
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Messages -->
        <a class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100"
           href="#">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
            </svg>
            <span class="mx-3">Messages</span>
        </a>

        <!-- Community -->
        <a class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100"
           href="#">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span class="mx-3">Community</span>
        </a>

        <!-- Analytics -->
        <a class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100"
           href="#">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span class="mx-3">Analytics</span>
        </a>

        <!-- Settings Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center w-full px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="mx-3">Settings</span>
            </button>
            
            <div x-show="open" @click.away="open = false" class="absolute left-0 w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg" x-cloak>
                <div class="py-1">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Profile Settings
                    </a>
                    <button @click="darkMode = !darkMode" class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">
                        Dark Mode
                    </button>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Security
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Notifications
                    </a>
                </div>
            </div>
        </div>

        <!-- Language Selector -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center w-full px-6 py-2 mt-4 text-gray-500 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                </svg>
                <span class="mx-3">Language</span>
            </button>
            
            <div x-show="open" @click.away="open = false" class="absolute left-0 w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg" x-cloak>
                <div class="py-1">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        English
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        French
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Spanish
                    </a>
                </div>
            </div>
        </div>

        <!-- Balance Display -->
        <div class="px-6 py-4 mt-4">
            <div class="p-4 bg-blue-50 rounded-lg">
                <div class="text-sm font-medium text-gray-600">Available Balance</div>
                <div class="mt-1 text-2xl font-semibold text-blue-600">
                    ${{ number_format(auth()->user()->balance ?? 0, 2) }}
                </div>
            </div>
        </div>
    </nav>
</div> 
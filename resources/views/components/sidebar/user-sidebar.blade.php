@props(['recommendedJobsCount' => 0, 'activeBidsCount' => 0, 'cartItemsCount' => 0, 'unreadMessagesCount' => 0])

<div x-data="{ sidebarOpen: false, darkMode: false }">
    <!-- Sidebar backdrop -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-20 transition-opacity bg-black bg-opacity-50 lg:hidden" @click="sidebarOpen = false" x-cloak></div>

    <!-- Sidebar -->
    <div x-show="sidebarOpen" class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-white dark:bg-gray-900 lg:translate-x-0 lg:static lg:inset-0" x-cloak>
        <!-- Logo -->
        <div class="flex items-center justify-center mt-8">
            <div class="flex items-center">
                <span class="text-2xl font-semibold text-gray-800 dark:text-white">Afrigig</span>
            </div>
        </div>

        <nav class="mt-10">
            <!-- Dashboard -->
            <x-sidebar.nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                <x-slot name="icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                </x-slot>
                Dashboard
            </x-sidebar.nav-link>

            <!-- Find Jobs -->
            <x-sidebar.nav-link href="{{ route('jobs.browse') }}" :active="request()->routeIs('jobs.*')">
                <x-slot name="icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </x-slot>
                Find Jobs
                @if($recommendedJobsCount > 0)
                    <x-ui.badge color="blue">{{ $recommendedJobsCount }}</x-ui.badge>
                @endif
            </x-sidebar.nav-link>

            <!-- My Bids -->
            <x-sidebar.nav-link href="{{ route('bids.index') }}" :active="request()->routeIs('bids.*')">
                <x-slot name="icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </x-slot>
                My Bids
                @if($activeBidsCount > 0)
                    <x-ui.badge color="green">{{ $activeBidsCount }}</x-ui.badge>
                @endif
            </x-sidebar.nav-link>

            <!-- Messages -->
            <x-sidebar.nav-link href="{{ route('messages.index') }}" :active="request()->routeIs('messages.*')">
                <x-slot name="icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                </x-slot>
                Messages
                @if($unreadMessagesCount > 0)
                    <x-ui.badge color="red">{{ $unreadMessagesCount }}</x-ui.badge>
                @endif
            </x-sidebar.nav-link>

            <!-- Settings -->
            <x-sidebar.nav-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.*')">
                <x-slot name="icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </x-slot>
                Settings
            </x-sidebar.nav-link>
        </nav>

        <!-- Balance Display -->
        <div class="px-6 py-4 mt-4">
            <div class="p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                <div class="text-sm font-medium text-gray-600 dark:text-gray-300">Available Balance</div>
                <div class="mt-1 text-2xl font-semibold text-blue-600 dark:text-blue-400">
                    ${{ number_format(auth()->user()->balance ?? 0, 2) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile menu button -->
    <div class="lg:hidden">
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-500 hover:text-gray-600 focus:outline-none focus:ring">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>
</div>

<!-- Off-canvas menu for mobile -->
<div x-data="{ open: false }" class="relative lg:hidden">
    <div x-show="open" class="fixed inset-0 flex z-40">
        <div x-show="open" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0" 
             @click="open = false">
            <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
        </div>
        <div x-show="open"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative flex-1 flex flex-col max-w-xs w-full bg-white dark:bg-gray-800">
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button @click="open = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <div class="flex-shrink-0 flex items-center px-4">
                    <img class="h-8 w-auto" src="/images/logo.svg" alt="Afrigig">
                </div>
                <nav class="mt-5 px-2 space-y-1">
                    <x-sidebar.nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <x-slot name="icon">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </x-slot>
                        Dashboard
                    </x-sidebar.nav-link>

                    <x-sidebar.nav-link :href="route('jobs.browse')" :active="request()->routeIs('jobs.browse')">
                        <x-slot name="icon">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </x-slot>
                        Find Jobs
                    </x-sidebar.nav-link>

                    <x-sidebar.nav-link :href="route('bids.index')" :active="request()->routeIs('bids.*')">
                        <x-slot name="icon">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </x-slot>
                        My Bids
                        @if(Auth::user()->bids()->where('status', 'pending')->count() > 0)
                            <span class="ml-auto inline-block py-0.5 px-3 text-xs rounded-full bg-primary-100 text-primary-800">
                                {{ Auth::user()->bids()->where('status', 'pending')->count() }}
                            </span>
                        @endif
                    </x-sidebar.nav-link>

                    <x-sidebar.nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                        <x-slot name="icon">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </x-slot>
                        Cart
                        @if(Auth::user()->cart()->count() > 0)
                            <span class="ml-auto inline-block py-0.5 px-3 text-xs rounded-full bg-primary-100 text-primary-800">
                                {{ Auth::user()->cart()->count() }}
                            </span>
                        @endif
                    </x-sidebar.nav-link>

                    <x-sidebar.nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')">
                        <x-slot name="icon">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </x-slot>
                        Messages
                        @if(Auth::user()->unreadMessages()->count() > 0)
                            <span class="ml-auto inline-block py-0.5 px-3 text-xs rounded-full bg-primary-100 text-primary-800">
                                {{ Auth::user()->unreadMessages()->count() }}
                            </span>
                        @endif
                    </x-sidebar.nav-link>
                </nav>
            </div>
            <div class="flex-shrink-0 flex border-t border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div>
                        <img class="inline-block h-10 w-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                    </div>
                    <div class="ml-3">
                        <p class="text-base font-medium text-gray-700 dark:text-gray-200">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-200">
                            Balance: ${{ number_format(Auth::user()->balance, 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-shrink-0 w-14">
            <!-- Force sidebar to shrink to fit close icon -->
        </div>
    </div>
</div>

<!-- Static sidebar for desktop -->
<div class="hidden lg:flex lg:flex-shrink-0">
    <div class="flex flex-col w-64">
        <div class="flex flex-col flex-grow bg-white dark:bg-gray-800 pt-5 pb-4 overflow-y-auto">
            <div class="flex items-center flex-shrink-0 px-4">
                <img class="h-8 w-auto" src="/images/logo.svg" alt="Afrigig">
            </div>
            <nav class="mt-5 flex-1 flex flex-col divide-y divide-gray-200 dark:divide-gray-700 overflow-y-auto" aria-label="Sidebar">
                <div class="px-2 space-y-1">
                    <x-sidebar.nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <x-slot name="icon">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </x-slot>
                        Dashboard
                    </x-sidebar.nav-link>

                    <x-sidebar.nav-link :href="route('jobs.browse')" :active="request()->routeIs('jobs.browse')">
                        <x-slot name="icon">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </x-slot>
                        Find Jobs
                    </x-sidebar.nav-link>

                    <x-sidebar.nav-link :href="route('bids.index')" :active="request()->routeIs('bids.*')">
                        <x-slot name="icon">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </x-slot>
                        My Bids
                        @if(Auth::user()->bids()->where('status', 'pending')->count() > 0)
                            <span class="ml-auto inline-block py-0.5 px-3 text-xs rounded-full bg-primary-100 text-primary-800">
                                {{ Auth::user()->bids()->where('status', 'pending')->count() }}
                            </span>
                        @endif
                    </x-sidebar.nav-link>

                    <x-sidebar.nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                        <x-slot name="icon">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </x-slot>
                        Cart
                        @if(Auth::user()->cart()->count() > 0)
                            <span class="ml-auto inline-block py-0.5 px-3 text-xs rounded-full bg-primary-100 text-primary-800">
                                {{ Auth::user()->cart()->count() }}
                            </span>
                        @endif
                    </x-sidebar.nav-link>

                    <x-sidebar.nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')">
                        <x-slot name="icon">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </x-slot>
                        Messages
                        @if(Auth::user()->unreadMessages()->count() > 0)
                            <span class="ml-auto inline-block py-0.5 px-3 text-xs rounded-full bg-primary-100 text-primary-800">
                                {{ Auth::user()->unreadMessages()->count() }}
                            </span>
                        @endif
                    </x-sidebar.nav-link>
                </div>
            </nav>
        </div>
        <div class="flex-shrink-0 flex border-t border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div>
                    <img class="inline-block h-10 w-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                </div>
                <div class="ml-3">
                    <p class="text-base font-medium text-gray-700 dark:text-gray-200">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-200">
                        Balance: ${{ number_format(Auth::user()->balance, 2) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
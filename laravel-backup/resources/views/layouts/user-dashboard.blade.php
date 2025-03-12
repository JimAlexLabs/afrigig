<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Afrigig') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
</head>
<body class="font-sans antialiased" :class="{ 'dark': darkMode }">
    <div class="min-h-screen bg-gray-100">
        <!-- Left Sidebar Navigation -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out" 
               x-data="{ open: true }" 
               :class="{'translate-x-0': open, '-translate-x-full': !open}">
            
            <!-- Logo -->
            <div class="flex items-center justify-between p-4 border-b">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto" alt="Afrigig Logo">
                    <span class="text-xl font-bold text-gray-900">Afrigig</span>
                </a>
                <button @click="open = !open" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto p-4 space-y-4">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 p-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-blue-50' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- My Cart -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center justify-between w-full p-2 rounded-lg text-gray-700 hover:bg-blue-50">
                        <div class="flex items-center space-x-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>My Cart</span>
                        </div>
                        <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs">
                            {{ auth()->user()->cart->count() ?? 0 }}
                        </span>
                    </button>
                </div>

                <!-- Notifications -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center justify-between w-full p-2 rounded-lg text-gray-700 hover:bg-blue-50">
                        <div class="flex items-center space-x-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span>Notifications</span>
                        </div>
                        <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    </button>
                </div>

                <!-- Messages -->
                <a href="{{ route('messages') }}" class="flex items-center space-x-2 p-2 rounded-lg text-gray-700 hover:bg-blue-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    <span>Messages</span>
                </a>

                <!-- Community -->
                <a href="{{ route('community') }}" class="flex items-center space-x-2 p-2 rounded-lg text-gray-700 hover:bg-blue-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Community</span>
                </a>

                <!-- Analytics -->
                <a href="{{ route('analytics') }}" class="flex items-center space-x-2 p-2 rounded-lg text-gray-700 hover:bg-blue-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span>Analytics</span>
                </a>

                <!-- Settings Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center justify-between w-full p-2 rounded-lg text-gray-700 hover:bg-blue-50">
                        <div class="flex items-center space-x-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Settings</span>
                        </div>
                        <svg class="h-5 w-5" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="{{ route('payment-methods') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Payment Methods</a>
                            <button @click="darkMode = !darkMode" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Theme: <span x-text="darkMode ? 'Dark' : 'Light'"></span>
                            </button>
                            <div class="border-t border-gray-200"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Language Selector -->
            <div class="border-t border-gray-200 p-4">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center justify-between w-full p-2 rounded-lg text-gray-700 hover:bg-gray-100">
                        <div class="flex items-center space-x-2">
                            <span class="flag-icon flag-icon-{{ App::getLocale() === 'en' ? 'gb' : App::getLocale() }}"></span>
                            <span>{{ config('languages')[App::getLocale()] }}</span>
                        </div>
                        <svg class="h-5 w-5" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute bottom-full left-0 mb-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                        <div class="py-1">
                            @foreach(config('languages') as $lang => $language)
                            <a href="{{ route('language.switch', $lang) }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <span class="flag-icon flag-icon-{{ $lang === 'en' ? 'gb' : $lang }}"></span>
                                <span>{{ $language }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="lg:pl-64">
            <!-- Top Navigation -->
            <nav class="bg-white border-b border-gray-200">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <button @click="$store.sidebar.toggle()" class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Balance Display -->
                        <div class="flex items-center">
                            <div class="text-gray-900 mr-4">
                                <span class="text-sm font-medium">Balance:</span>
                                <span class="text-lg font-bold">${{ number_format(auth()->user()->balance, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="py-10">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @if(session('tour') !== 'completed' && request()->routeIs('dashboard'))
                        <div x-data="{ show: true }" x-show="show" class="mb-6 bg-blue-50 p-4 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">
                                        Welcome to Afrigig!
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Would you like to take a quick tour of our platform?</p>
                                    </div>
                                    <div class="mt-4">
                                        <div class="-mx-2 -my-1.5 flex">
                                            <button @click="$store.tour.start()" class="px-3 py-2 rounded-md text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-200">
                                                Start Tour
                                            </button>
                                            <button @click="show = false" class="ml-3 px-3 py-2 rounded-md text-sm font-medium text-blue-700 hover:bg-blue-100">
                                                Maybe Later
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Notification Modal -->
    <div x-data="{ show: false, message: '' }"
         @notification.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
         x-show="show"
         x-transition:enter="transform ease-out duration-300 transition"
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end z-50">
        <div class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto">
            <div class="rounded-lg shadow-xs overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p x-text="message" class="text-sm leading-5 font-medium text-gray-900"></p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="show = false" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @stack('modals')
    @stack('scripts')
</body>
</html> 
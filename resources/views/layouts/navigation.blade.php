<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                            {{ __('Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.jobs.index')" :active="request()->routeIs('admin.jobs.*')">
                            {{ __('Jobs') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.payments')" :active="request()->routeIs('admin.payments')">
                            {{ __('Payments') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.skill-assessments.index')" :active="request()->routeIs('admin.skill-assessments.*')">
                            {{ __('Skill Assessments') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.index')">
                            {{ __('Jobs') }}
                        </x-nav-link>
                        @if(auth()->user()->role === 'client')
                            <x-nav-link :href="route('jobs.create')" :active="request()->routeIs('jobs.create')">
                                {{ __('Post Job') }}
                            </x-nav-link>
                        @endif
                        <x-nav-link :href="route('skill-assessments.index')" :active="request()->routeIs('skill-assessments.*')">
                            {{ __('Skill Assessments') }}
                        </x-nav-link>
                    @endif

                    <!-- Admin Navigation -->
                    @if (auth()->user()->isAdmin())
                        <div class="space-y-1">
                            <!-- Skill Assessments -->
                            <x-nav-dropdown>
                                <x-slot name="trigger">
                                    <button class="flex items-center w-full text-left px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                        Skill Assessments
                                        <svg class="ml-auto h-5 w-5 transform group-hover:text-gray-400 transition-colors ease-in-out duration-150" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('admin.skill-assessments.index')" :active="request()->routeIs('admin.skill-assessments.index')">
                                        Manage Assessments
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.skill-assessments.results')" :active="request()->routeIs('admin.skill-assessments.results')">
                                        View Results
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.skill-assessments.feedback')" :active="request()->routeIs('admin.skill-assessments.feedback')">
                                        Pending Feedback
                                    </x-dropdown-link>
                                </x-slot>
                            </x-nav-dropdown>
                        </div>
                    @endif

                    <!-- User Navigation -->
                    @if (!auth()->user()->isAdmin())
                        <!-- Skill Assessments -->
                        <x-nav-link :href="route('skill-assessments.index')" :active="request()->routeIs('skill-assessments.*')">
                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                            Skill Assessments
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                    {{ __('Users') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.jobs.index')" :active="request()->routeIs('admin.jobs.*')">
                    {{ __('Jobs') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.payments')" :active="request()->routeIs('admin.payments')">
                    {{ __('Payments') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.skill-assessments.index')" :active="request()->routeIs('admin.skill-assessments.*')">
                    {{ __('Skill Assessments') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.index')">
                    {{ __('Jobs') }}
                </x-responsive-nav-link>
                @if(auth()->user()->role === 'client')
                    <x-responsive-nav-link :href="route('jobs.create')" :active="request()->routeIs('jobs.create')">
                        {{ __('Post Job') }}
                    </x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('skill-assessments.index')" :active="request()->routeIs('skill-assessments.*')">
                    {{ __('Skill Assessments') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

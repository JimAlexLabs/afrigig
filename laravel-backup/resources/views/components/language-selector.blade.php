<div x-data="{ open: false, currentLocale: '{{ app()->getLocale() }}' }" class="relative">
    <button @click="open = !open" type="button" class="flex items-center gap-x-1 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
        <span x-text="currentLocale === 'en' ? 'English' : (currentLocale === 'fr' ? 'Français' : 'Español')"></span>
        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>

    <div x-show="open" @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white dark:bg-gray-800 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        role="menu" aria-orientation="vertical" aria-labelledby="language-menu-button" tabindex="-1">
        
        <a href="{{ route('language.switch', 'en') }}" @click="currentLocale = 'en'; open = false"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
            :class="{ 'bg-gray-100 dark:bg-gray-700': currentLocale === 'en' }"
            role="menuitem" tabindex="-1">
            English
        </a>

        <a href="{{ route('language.switch', 'fr') }}" @click="currentLocale = 'fr'; open = false"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
            :class="{ 'bg-gray-100 dark:bg-gray-700': currentLocale === 'fr' }"
            role="menuitem" tabindex="-1">
            Français
        </a>

        <a href="{{ route('language.switch', 'es') }}" @click="currentLocale = 'es'; open = false"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
            :class="{ 'bg-gray-100 dark:bg-gray-700': currentLocale === 'es' }"
            role="menuitem" tabindex="-1">
            Español
        </a>
    </div>
</div> 
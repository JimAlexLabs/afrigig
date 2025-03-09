@inject('pixabay', 'App\Services\PixabayService')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($image = $pixabay->getProfileImage())
                <div class="mb-8">
                    <x-feature-image 
                        :imageUrl="$image['largeImageURL']" 
                        :alt="'Professional Profile'"
                        class="h-64 w-full"
                    />
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
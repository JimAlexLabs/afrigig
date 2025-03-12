<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <img src="{{ asset('images/logo.svg') }}" alt="Afrigig Logo" class="w-48">
        </x-slot>

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Join Afrigig</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Connect with Africa's top talent and opportunities</p>
        </div>

        <x-validation-errors class="mb-4" />

        <!-- Social Login Buttons -->
        <div class="space-y-4 mb-6">
            <a href="{{ route('social.login', 'google') }}" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12.545,12.151L12.545,12.151c0,1.054,0.947,1.91,2.012,1.91h3.964c-0.404,2.349-2.461,4.073-4.821,4.073c-2.694,0-4.878-2.184-4.878-4.878c0-2.694,2.184-4.878,4.878-4.878c1.184,0,2.271,0.424,3.115,1.128l1.733-1.733C17.457,6.851,15.969,6.164,14.3,6.164c-3.922,0-7.101,3.179-7.101,7.101c0,3.922,3.179,7.101,7.101,7.101c4.163,0,6.959-2.796,6.959-6.959c0-0.547-0.074-1.076-0.207-1.579h-8.507V12.151z"/>
                </svg>
                Sign up with Google
            </a>

            <a href="{{ route('social.login', 'linkedin') }}" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                </svg>
                Sign up with LinkedIn
            </a>
        </div>

        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Or sign up with email</span>
            </div>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Full Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2 text-sm text-gray-600 dark:text-gray-400">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ms-4 bg-indigo-600 hover:bg-indigo-700">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>

        <div class="mt-6 text-center text-xs text-gray-500 dark:text-gray-400">
            By signing up, you agree to Afrigig's
            <a href="{{ route('terms') }}" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Terms of Service</a>
            and
            <a href="{{ route('policy') }}" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Privacy Policy</a>
        </div>
    </x-authentication-card>
</x-guest-layout>

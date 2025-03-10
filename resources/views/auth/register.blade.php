<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Create Account</h2>
        <p class="text-gray-600 mt-2">Join our community of professionals</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-input-group">
            <i class="fas fa-user"></i>
            <x-text-input id="name" 
                         class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                         type="text" 
                         name="name" 
                         :value="old('name')" 
                         placeholder="Full name"
                         required 
                         autofocus 
                         autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="form-input-group">
            <i class="fas fa-envelope"></i>
            <x-text-input id="email" 
                         class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                         type="email" 
                         name="email" 
                         :value="old('email')" 
                         placeholder="Email address"
                         required 
                         autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="form-input-group">
            <i class="fas fa-lock"></i>
            <x-text-input id="password" 
                         class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                         type="password"
                         name="password"
                         placeholder="Password"
                         required 
                         autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="form-input-group">
            <i class="fas fa-lock"></i>
            <x-text-input id="password_confirmation" 
                         class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                         type="password"
                         name="password_confirmation" 
                         placeholder="Confirm password"
                         required 
                         autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms and Conditions -->
        <div class="mt-4">
            <label class="flex items-center">
                <input type="checkbox" 
                       name="terms" 
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                       required>
                <span class="ml-2 text-sm text-gray-600">
                    I agree to the 
                    <a href="{{ route('terms') }}" class="text-blue-600 hover:text-blue-800" target="_blank">Terms and Conditions</a>
                </span>
            </label>
            <x-input-error :messages="$errors->get('terms')" class="mt-2" />
        </div>

        <button type="submit" class="w-full mt-6 py-3 px-4 text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 rounded-lg font-semibold shadow-lg transform hover:scale-[1.02] transition-all duration-200">
            Create Account
        </button>
    </form>

    <div class="mt-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Or register with</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4">
            <a href="{{ route('auth.google') }}" class="social-login-button">
                <i class="fab fa-google text-red-500 mr-2"></i>
                Google
            </a>
            <a href="{{ route('auth.linkedin') }}" class="social-login-button">
                <i class="fab fa-linkedin text-blue-500 mr-2"></i>
                LinkedIn
            </a>
        </div>
    </div>

    <p class="mt-8 text-center text-sm text-gray-600">
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-800">
            Sign in
        </a>
    </p>
</x-guest-layout>

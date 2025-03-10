<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Welcome Back!</h2>
        <p class="text-gray-600 mt-2">Please sign in to your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

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
                         autofocus 
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
                         autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mb-6">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-800" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" class="w-full py-3 px-4 text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 rounded-lg font-semibold shadow-lg transform hover:scale-[1.02] transition-all duration-200">
            Sign in
        </button>
    </form>

    <div class="mt-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Or continue with</span>
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
        Don't have an account?
        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-800">
            Sign up now
        </a>
    </p>
</x-guest-layout>

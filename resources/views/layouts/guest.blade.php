<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Afrigig') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .auth-illustration {
                background-image: url('{{ asset('images/auth-bg.jpg') }}');
                background-size: cover;
                background-position: center;
            }
            
            .form-input-group {
                position: relative;
                margin-bottom: 1.5rem;
            }
            
            .form-input-group input {
                padding-left: 3rem;
            }
            
            .form-input-group i {
                position: absolute;
                left: 1rem;
                top: 50%;
                transform: translateY(-50%);
                color: #6B7280;
            }

            .social-login-button {
                @apply flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
            }

            .auth-card {
                @apply w-full p-8 bg-white rounded-2xl shadow-xl sm:max-w-md;
                backdrop-filter: blur(10px);
                background: rgba(255, 255, 255, 0.95);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen md:flex">
            <!-- Left Side - Illustration -->
            <div class="hidden md:flex md:w-1/2 auth-illustration">
                <div class="flex flex-col justify-center w-full px-12 bg-gradient-to-t from-blue-900/90 to-blue-900/50">
                    <div class="mb-8">
                        <a href="/" class="flex items-center">
                            <img src="{{ asset('images/logo.svg') }}" alt="Afrigig Logo" class="h-12 w-auto">
                        </a>
                    </div>
                    <h1 class="text-4xl font-bold text-white mb-6">Welcome to Afrigig</h1>
                    <p class="text-xl text-blue-100 mb-8">Join our community of African professionals and connect with global opportunities.</p>
                    <div class="flex space-x-4">
                        <div class="bg-white/10 backdrop-blur-md rounded-lg p-4">
                            <div class="text-2xl font-bold text-white mb-1">195K+</div>
                            <div class="text-blue-100 text-sm">Professionals</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-lg p-4">
                            <div class="text-2xl font-bold text-white mb-1">2.8M+</div>
                            <div class="text-blue-100 text-sm">Projects</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-lg p-4">
                            <div class="text-2xl font-bold text-white mb-1">1.2K+</div>
                            <div class="text-blue-100 text-sm">Active Jobs</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="flex items-center justify-center w-full md:w-1/2 px-6 py-8 md:px-12 bg-gradient-to-br from-gray-50 to-gray-100">
                <div class="auth-card">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>

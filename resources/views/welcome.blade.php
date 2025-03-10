<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Afrigig') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Add Font Awesome for social icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body class="antialiased">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <img class="h-8 w-auto" src="{{ asset('images/logo.svg') }}" alt="Afrigig Logo">
                        </div>
                    </div>
                    <div class="flex items-center">
                        @if (Route::has('login'))
                            <div class="space-x-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Log in</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Register</a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative bg-gradient-to-r from-blue-600 to-indigo-700 pt-32 pb-20 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h1 class="text-4xl md:text-6xl font-bold mb-6">
                            Empowering African Talent
                        </h1>
                        <p class="text-xl mb-8">
                            Connecting skilled African professionals with global opportunities. Join our platform to showcase your expertise and find exciting projects.
                        </p>
                        <div class="space-x-4">
                            <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition">
                                Get Started
                            </a>
                            <a href="#about" class="border border-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:text-blue-600 transition">
                                Learn More
                            </a>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <img src="{{ asset('images/hero-image.png') }}" alt="African Professionals" class="rounded-lg shadow-xl">
                    </div>
                </div>
            </div>
        </div>

        <!-- About Section -->
        <div id="about" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">About Afrigig</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        We're on a mission to showcase African talent to the world. Our platform connects skilled professionals with opportunities that match their expertise.
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-xl shadow-md">
                        <div class="text-blue-600 mb-4">
                            <i class="fas fa-users text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-4">Community</h3>
                        <p class="text-gray-600">Join our growing community of African professionals and expand your network.</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-md">
                        <div class="text-blue-600 mb-4">
                            <i class="fas fa-briefcase text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-4">Opportunities</h3>
                        <p class="text-gray-600">Access high-quality job opportunities from reputable organizations worldwide.</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-md">
                        <div class="text-blue-600 mb-4">
                            <i class="fas fa-graduation-cap text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-4">Growth</h3>
                        <p class="text-gray-600">Enhance your skills through our training programs and skill assessments.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Impact Section -->
        <div class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Impact</h2>
                    <p class="text-xl text-gray-600">Making a difference in the African tech ecosystem</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-4xl font-bold text-blue-600 mb-2">5000+</div>
                        <div class="text-gray-600">Registered Professionals</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-blue-600 mb-2">1000+</div>
                        <div class="text-gray-600">Completed Projects</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-blue-600 mb-2">50+</div>
                        <div class="text-gray-600">Countries Reached</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-blue-600 mb-2">$2M+</div>
                        <div class="text-gray-600">Paid to Freelancers</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">About Us</h3>
                        <p class="text-gray-400">Empowering African talent through technology and connecting them with global opportunities.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white">Home</a></li>
                            <li><a href="#about" class="hover:text-white">About</a></li>
                            <li><a href="#" class="hover:text-white">Jobs</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="mt-12 pt-8 border-t border-gray-800 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} Afrigig. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html> 
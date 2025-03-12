<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Afrigig') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Add Font Awesome for social icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <!-- Add Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <!-- Add Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <!-- Navigation -->
        <nav class="bg-white/90 backdrop-blur-md shadow-lg fixed w-full z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <img class="h-12 w-auto hover:scale-105 transition-transform duration-300" src="{{ asset('images/logo.svg') }}" alt="Afrigig Logo">
                        </div>
                    </div>
                    <div class="flex items-center space-x-6">
                        @if (Route::has('login'))
                            <div class="flex items-center space-x-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" 
                                       class="text-gray-700 hover:text-blue-600 font-semibold transition-colors duration-300">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="text-gray-700 hover:text-blue-600 font-semibold transition-colors duration-300">
                                        Log in
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" 
                                           class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-full font-semibold hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                                            Register
                                        </a>
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
                        <img src="{{ asset('images/hero-image.svg') }}" alt="African Professionals" class="rounded-lg shadow-xl w-full h-auto">
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

        <!-- Contact Section -->
        <div class="py-20 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Get in Touch</h2>
                    <p class="text-xl text-gray-600">We're here to help you succeed</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Email Contact -->
                    <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="text-blue-600 mb-4">
                            <i class="fas fa-envelope text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-4">Email Us</h3>
                        <p class="text-gray-600 mb-4">Get in touch with our support team</p>
                        <a href="mailto:support@afrigig.org" class="text-blue-600 hover:text-blue-700 font-semibold">
                            support@afrigig.org
                        </a>
                    </div>

                    <!-- Live Chat -->
                    <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="text-green-600 mb-4">
                            <i class="fas fa-comments text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-4">Live Chat</h3>
                        <p class="text-gray-600 mb-4">Chat with our support team</p>
                        <div class="inline-flex items-center space-x-2">
                            <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                            <span class="text-green-600 font-semibold">Online</span>
                        </div>
                    </div>

                    <!-- Office Location -->
                    <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="text-blue-600 mb-4">
                            <i class="fas fa-building text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-4">Visit Us</h3>
                        <p class="text-gray-600 mb-4">Manchester Office</p>
                        <address class="text-gray-600 not-italic">
                            125 Deansgate<br>
                            Manchester M3 2BY<br>
                            United Kingdom
                        </address>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonials Section -->
        <div class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">What Our Users Say</h2>
                    <p class="text-xl text-gray-600">Success stories from our community</p>
                </div>

                <!-- Testimonials Slider -->
                <div class="relative" x-data="{ currentSlide: 0 }">
                    <div class="overflow-hidden">
                        <div class="flex transition-transform duration-500" 
                             :style="'transform: translateX(-' + (currentSlide * 100) + '%)'">
                            <!-- Testimonial Cards -->
                            @foreach ([
                                ['name' => 'Sarah Johnson', 'role' => 'Software Developer', 'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&auto=format&fit=crop&w=256&h=256&q=80', 'text' => 'Afrigig has been instrumental in helping me connect with global opportunities. The platform is intuitive and the support team is amazing!'],
                                ['name' => 'Michael Chen', 'role' => 'UX Designer', 'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&auto=format&fit=crop&w=256&h=256&q=80', 'text' => 'As a designer, I found the perfect projects through Afrigig. The platform understands the needs of creative professionals.'],
                                ['name' => 'David Wilson', 'role' => 'Project Manager', 'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&auto=format&fit=crop&w=256&h=256&q=80', 'text' => 'The quality of talent on Afrigig is exceptional. I have built an amazing team through this platform.']
                            ] as $testimonial)
                            <div class="w-full flex-shrink-0 px-4">
                                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                                    <div class="flex items-center mb-6">
                                        <img class="w-16 h-16 rounded-full object-cover mr-4" 
                                             src="{{ $testimonial['image'] }}" 
                                             alt="{{ $testimonial['name'] }}">
                                        <div>
                                            <h4 class="text-lg font-semibold">{{ $testimonial['name'] }}</h4>
                                            <p class="text-gray-600">{{ $testimonial['role'] }}</p>
                                        </div>
                                    </div>
                                    <p class="text-gray-600 italic">"{{ $testimonial['text'] }}"</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <button class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white p-3 rounded-full shadow-lg hover:bg-gray-50 focus:outline-none"
                            @click="currentSlide = (currentSlide - 1 + 3) % 3">
                        <i class="fas fa-chevron-left text-blue-600"></i>
                    </button>
                    <button class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white p-3 rounded-full shadow-lg hover:bg-gray-50 focus:outline-none"
                            @click="currentSlide = (currentSlide + 1) % 3">
                        <i class="fas fa-chevron-right text-blue-600"></i>
                    </button>

                    <!-- Dots Navigation -->
                    <div class="flex justify-center mt-8 space-x-2">
                        <template x-for="(_, index) in [0, 1, 2]" :key="index">
                            <button class="w-3 h-3 rounded-full transition-colors duration-200"
                                    :class="currentSlide === index ? 'bg-blue-600' : 'bg-gray-300'"
                                    @click="currentSlide = index">
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Form Section -->
        <div class="py-20 bg-gray-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Need Assistance?</h2>
                    <p class="text-xl text-gray-600">Let us help you find the perfect solution</p>
                </div>

                <form class="bg-white p-8 rounded-xl shadow-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Request Details</label>
                            <textarea rows="4" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-transparent"></textarea>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transform hover:scale-[1.02] transition-all duration-300 shadow-lg hover:shadow-xl">
                            Send Request
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Latest Orders Section -->
        <div class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Latest Orders</h2>
                    <p class="text-xl text-gray-600">Available opportunities for our writers</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-xl shadow-lg">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Topic/Title</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Deadline</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Pages</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Salary</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ([
                                ['title' => 'Investment Management', 'deadline' => '8d 18h', 'pages' => 3, 'salary' => '$41.50'],
                                ['title' => 'Draft', 'deadline' => '2d 8h', 'pages' => 6, 'salary' => '$39.00'],
                                ['title' => 'Legal Issues in Psychology', 'deadline' => '11d 2h', 'pages' => 5, 'salary' => '$15.00'],
                                ['title' => 'Discussion #2 Volcy', 'deadline' => '1d 12h', 'pages' => 2, 'salary' => '$11.00'],
                                ['title' => 'Restaurant Review Analysis', 'deadline' => '3d 0h', 'pages' => 2, 'salary' => '$11.00']
                            ] as $order)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $order['title'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $order['deadline'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $order['pages'] }}</td>
                                <td class="px-6 py-4 text-sm text-blue-600 font-semibold">{{ $order['salary'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-gray-600 mb-4">Current orders: 5 | Total fees offered: $117.50</p>
                    <a href="#" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10">
                        View All Orders
                    </a>
                </div>
            </div>
        </div>

        <!-- Advantages Section -->
        <div class="py-20 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Choose Us</h2>
                    <p class="text-xl text-gray-600">Benefits of working with Afrigig</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ([
                        ['icon' => 'fa-dollar-sign', 'title' => 'Competitive Salaries', 'description' => 'Earn what you deserve with our competitive pay rates'],
                        ['icon' => 'fa-clock', 'title' => 'Flexible Schedule', 'description' => 'Work on your own terms, whenever and wherever you want'],
                        ['icon' => 'fa-chart-line', 'title' => 'Career Growth', 'description' => 'Opportunities for personal and professional development'],
                        ['icon' => 'fa-shield-alt', 'title' => 'Fair Policy', 'description' => 'Transparent and fair policies for all our writers'],
                        ['icon' => 'fa-headset', 'title' => '24/7 Support', 'description' => 'Round-the-clock assistance whenever you need it'],
                        ['icon' => 'fa-tasks', 'title' => 'Constant Flow', 'description' => 'Regular stream of orders throughout the year']
                    ] as $advantage)
                    <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="text-blue-600 mb-4">
                            <i class="fas {{ $advantage['icon'] }} text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-4">{{ $advantage['title'] }}</h3>
                        <p class="text-gray-600">{{ $advantage['description'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="py-20 bg-blue-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div class="bg-white/10 backdrop-blur-md p-8 rounded-xl">
                        <div class="text-5xl font-bold text-white mb-2">195,427</div>
                        <div class="text-blue-100">Professional Writers</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-8 rounded-xl">
                        <div class="text-5xl font-bold text-white mb-2">2,844,076</div>
                        <div class="text-blue-100">Completed Orders</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-8 rounded-xl">
                        <div class="text-5xl font-bold text-white mb-2">1,247</div>
                        <div class="text-blue-100">Current Online Jobs</div>
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
                            <li><a href="#" class="hover:text-white">Dashboard</a></li>
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

        <!-- AI Chat Widget -->
        <div x-data="{ isOpen: false, messages: [], userInput: '' }" class="fixed bottom-4 right-4 z-50">
            <!-- Chat Button -->
            <button @click="isOpen = !isOpen" 
                    class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg flex items-center space-x-2">
                <i class="fas fa-comments text-xl"></i>
                <span class="hidden md:inline">Chat with us</span>
            </button>

            <!-- Chat Window -->
            <div x-show="isOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-90"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-90"
                 class="absolute bottom-16 right-0 w-96 bg-white rounded-lg shadow-xl">
                
                <!-- Chat Header -->
                <div class="bg-blue-600 text-white p-4 rounded-t-lg flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-robot"></i>
                        <span class="font-semibold">Afrigig Assistant</span>
                    </div>
                    <button @click="isOpen = false" class="text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Chat Messages -->
                <div class="h-96 overflow-y-auto p-4 space-y-4">
                    <!-- Welcome Message -->
                    <div class="flex items-start space-x-2">
                        <div class="bg-blue-100 rounded-lg p-3 max-w-[80%]">
                            <p class="text-gray-800">Hello! 👋 I'm your Afrigig assistant. How can I help you today?</p>
                        </div>
                    </div>

                    <!-- Dynamic Messages -->
                    <template x-for="message in messages" :key="message.id">
                        <div :class="{'flex items-start space-x-2': message.type === 'assistant',
                                    'flex items-start space-x-2 justify-end': message.type === 'user'}">
                            <div :class="{'bg-blue-100 rounded-lg p-3 max-w-[80%]': message.type === 'assistant',
                                        'bg-blue-600 text-white rounded-lg p-3 max-w-[80%]': message.type === 'user'}">
                                <p x-text="message.text"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Chat Input -->
                <div class="border-t p-4">
                    <form @submit.prevent="
                        if (userInput.trim()) {
                            messages.push({id: Date.now(), type: 'user', text: userInput});
                            setTimeout(() => {
                                messages.push({
                                    id: Date.now() + 1,
                                    type: 'assistant',
                                    text: 'Thank you for your message! One of our team members will get back to you shortly. In the meantime, feel free to explore our available jobs or check out our writer registration process.'
                                });
                            }, 1000);
                            userInput = '';
                        }
                    ">
                        <div class="flex space-x-2">
                            <input type="text" 
                                   x-model="userInput"
                                   class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                                   placeholder="Type your message...">
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html> 
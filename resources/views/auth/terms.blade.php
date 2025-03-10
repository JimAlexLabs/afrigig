<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Afrigig') }} - Terms and Conditions</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="/" class="flex-shrink-0">
                            <img class="h-8 w-auto" src="{{ asset('images/logo.svg') }}" alt="Afrigig Logo">
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <main class="py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <h1 class="text-2xl font-bold text-gray-900 mb-6">Terms and Conditions</h1>
                        
                        <div class="space-y-8 text-gray-600">
                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">1. Platform Ownership and Rights</h2>
                                <p class="text-base leading-relaxed">Afrigig and all its content, features, and functionality are owned by Afrigig and are protected by international copyright, trademark, and other intellectual property rights laws.</p>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">2. User Account and Responsibilities</h2>
                                <ul class="text-base leading-relaxed list-disc pl-5 space-y-2">
                                    <li>Users are responsible for maintaining the confidentiality of their account credentials.</li>
                                    <li>Afrigig reserves the right to suspend or terminate accounts at its sole discretion.</li>
                                    <li>Users grant Afrigig a worldwide, non-exclusive, royalty-free license to use, reproduce, and distribute any content they submit.</li>
                                </ul>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">3. Platform Usage</h2>
                                <ul class="text-base leading-relaxed list-disc pl-5 space-y-2">
                                    <li>Afrigig maintains full control over user access and platform features.</li>
                                    <li>The platform may be modified, suspended, or discontinued at any time without notice.</li>
                                    <li>Users must comply with all applicable laws and platform guidelines.</li>
                                </ul>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">4. Payment and Fees</h2>
                                <ul class="text-base leading-relaxed list-disc pl-5 space-y-2">
                                    <li>Afrigig reserves the right to modify fee structures at any time.</li>
                                    <li>Platform fees are non-refundable unless otherwise stated.</li>
                                    <li>Users are responsible for all applicable taxes and charges.</li>
                                </ul>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">5. Content Rights</h2>
                                <ul class="text-base leading-relaxed list-disc pl-5 space-y-2">
                                    <li>Afrigig retains the right to use, modify, and distribute user-generated content.</li>
                                    <li>Users waive any moral rights to content submitted on the platform.</li>
                                    <li>Afrigig may remove any content at its sole discretion.</li>
                                </ul>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">6. Limitation of Liability</h2>
                                <p class="text-base leading-relaxed">Afrigig shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from platform use.</p>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">7. Dispute Resolution</h2>
                                <p class="text-base leading-relaxed">Any disputes shall be resolved through arbitration in the jurisdiction of Afrigig's choosing.</p>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">8. Modifications</h2>
                                <p class="text-base leading-relaxed">Afrigig reserves the right to modify these terms at any time without notice. Continued use of the platform constitutes acceptance of modified terms.</p>
                            </section>
                        </div>

                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-sm text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back
                            </a>
                            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <i class="fas fa-print mr-2"></i>
                                Print Terms
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html> 
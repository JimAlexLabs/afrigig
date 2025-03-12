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
                        <h1 class="text-2xl font-bold text-gray-900 mb-6">Terms of Service</h1>
                        
                        <div class="space-y-8 text-gray-600">
                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">1. Acceptance of Terms</h2>
                                <p class="text-base leading-relaxed">By accessing and using Afrigig, you agree to be bound by these Terms of Service and all applicable laws and regulations.</p>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">2. User Accounts</h2>
                                <ul class="text-base leading-relaxed list-disc pl-5 space-y-2">
                                    <li>You must be at least 18 years old to use our services</li>
                                    <li>You are responsible for maintaining the security of your account</li>
                                    <li>You must provide accurate and complete information</li>
                                    <li>You may not use another person's account without permission</li>
                                </ul>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">3. Platform Rules</h2>
                                <ul class="text-base leading-relaxed list-disc pl-5 space-y-2">
                                    <li>Treat all users with respect and professionalism</li>
                                    <li>Do not post false, misleading, or fraudulent content</li>
                                    <li>Do not engage in spam or harassment</li>
                                    <li>Do not attempt to manipulate the platform's systems</li>
                                </ul>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">4. Payments and Fees</h2>
                                <ul class="text-base leading-relaxed list-disc pl-5 space-y-2">
                                    <li>All fees are clearly displayed before confirmation</li>
                                    <li>We charge a platform fee for successful transactions</li>
                                    <li>Payment terms are specified in each job contract</li>
                                    <li>Refunds are handled according to our refund policy</li>
                                </ul>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">5. Intellectual Property</h2>
                                <ul class="text-base leading-relaxed list-disc pl-5 space-y-2">
                                    <li>Users retain rights to their original content</li>
                                    <li>You grant us license to use content for platform purposes</li>
                                    <li>Respect others' intellectual property rights</li>
                                    <li>Report any copyright violations</li>
                                </ul>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">6. Service Quality</h2>
                                <ul class="text-base leading-relaxed list-disc pl-5 space-y-2">
                                    <li>Complete work as specified in job agreements</li>
                                    <li>Maintain professional communication</li>
                                    <li>Meet agreed-upon deadlines</li>
                                    <li>Provide quality work that meets requirements</li>
                                </ul>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">7. Dispute Resolution</h2>
                                <p class="text-base leading-relaxed">In case of disputes:</p>
                                <ul class="text-base leading-relaxed list-disc pl-5 space-y-2">
                                    <li>First attempt direct resolution</li>
                                    <li>Use our platform's dispute resolution system</li>
                                    <li>Follow arbitration procedures if necessary</li>
                                    <li>Comply with final decisions</li>
                                </ul>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">8. Termination</h2>
                                <p class="text-base leading-relaxed">We reserve the right to:</p>
                                <ul class="text-base leading-relaxed list-disc pl-5 space-y-2">
                                    <li>Suspend or terminate accounts for violations</li>
                                    <li>Remove content that violates terms</li>
                                    <li>Ban users who abuse the platform</li>
                                </ul>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">9. Limitation of Liability</h2>
                                <p class="text-base leading-relaxed">Afrigig is not liable for:</p>
                                <ul class="text-base leading-relaxed list-disc pl-5 space-y-2">
                                    <li>User-generated content</li>
                                    <li>Disputes between users</li>
                                    <li>Loss of profits or data</li>
                                    <li>Service interruptions</li>
                                </ul>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">10. Changes to Terms</h2>
                                <p class="text-base leading-relaxed">We may modify these terms at any time. Continued use of the platform constitutes acceptance of new terms.</p>
                            </section>

                            <section>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3">11. Contact Information</h2>
                                <p class="text-base leading-relaxed">For questions about these terms, contact us at:</p>
                                <ul class="text-base leading-relaxed list-disc pl-5 space-y-2">
                                    <li>Email: legal@afrigig.com</li>
                                    <li>Address: [Your Business Address]</li>
                                </ul>
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
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900">
    <!-- Navigation placeholder -->
    @include('layouts.navigation')


    <div class="container mx-auto px-4 py-12 max-w-6xl">
        <!-- Header Section -->
        <div class="text-center mb-12 animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 dark:text-white mb-4">
                Share Your Experience
            </h1>

        </div>

        <!-- Review Cards Container -->
        <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
            <!-- Good Review Card -->
            <div class="card-hover animate-slide-up">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 border border-gray-100 dark:border-gray-700 relative overflow-hidden">
                    <!-- Decorative elements -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full -translate-y-16 translate-x-16 opacity-10"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-green-300 to-emerald-400 rounded-full translate-y-12 -translate-x-12 opacity-10"></div>

                    <!-- Icon -->
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center animate-float">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">
                        Good Review
                    </h2>

                    <!-- Form -->
                    <form class="space-y-6" action="{{ route('good_feedback.submit') }}" method="POST">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Share your positive experience
                            </label>
                            <textarea
                                class="input-focus w-full px-4 py-4 h-32 border-2 border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:border-green-500 focus:ring-0 bg-white dark:bg-gray-700 dark:text-white text-gray-800 placeholder-gray-500 dark:placeholder-gray-400 resize-none"
                                placeholder="Tell us what you loved about our service..."
                                required
                            ></textarea>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            Submit Good Review
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bad Review Card -->
            <div class="card-hover animate-slide-up" style="animation-delay: 0.2s;">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 border border-gray-100 dark:border-gray-700 relative overflow-hidden">
                    <!-- Decorative elements -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-400 to-pink-500 rounded-full -translate-y-16 translate-x-16 opacity-10"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-red-300 to-pink-400 rounded-full translate-y-12 -translate-x-12 opacity-10"></div>

                    <!-- Icon -->
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-red-400 to-pink-500 rounded-2xl flex items-center justify-center animate-float" style="animation-delay: 0.5s;">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">
                        Feedback for Improvement
                    </h2>

                    <!-- Form -->
                    <form class="space-y-6" action="{{ route('bad_feedback.submit') }}" method="POST">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Help us improve our service
                            </label>
                            <textarea
                                class="input-focus w-full px-4 py-4 h-32 border-2 border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:border-red-500 focus:ring-0 bg-white dark:bg-gray-700 dark:text-white text-gray-800 placeholder-gray-500 dark:placeholder-gray-400 resize-none"
                                placeholder="Tell us what we can improve..."
                                required
                            ></textarea>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            Submit Feedback
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Dark mode toggle (if needed)
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
        }
    </script>
</body>

</html>


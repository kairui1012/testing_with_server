<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Simple Feedback List</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50">
    @include('layouts.navigation')
    <div class="max-w-[80%] mx-auto">
        <h1 class="text-2xl font-bold mb-10 mt-10">Feedback</h1>
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden ">
        <!-- Good Feedback -->
        <div class="p-8 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
            <div class="flex items-center gap-6">
                <div class="bg-green-600  text-white text-sm font-bold px-4 py-3 rounded-xl shadow-lg w-24 text-center">
                    GOOD
                </div>
                <textarea
                    class="flex-1 p-4 border-2 border-gray-400 rounded-xl text-sm resize-none focus:outline-none focus:border-green-400 "
                    rows="2"
                    placeholder="Share what went well..."
                ></textarea>
            </div>
        </div>

        <!-- Bad Feedback -->
        <div class="p-8 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
            <div class="flex items-center gap-6">
                <div class="bg-red-600 text-white text-sm font-bold px-4 py-3 rounded-xl shadow-lg w-24 text-center">
                    BAD
                </div>
                <textarea
                    class="flex-1 p-4 border-2 border-gray-400 rounded-xl text-sm resize-none focus:outline-none focus:border-red-400 "
                    rows="2"
                    placeholder="What could be improved..."
                ></textarea>
            </div>
        </div>

        <!-- Remark -->
        <div class="p-8 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
            <div class="flex items-center gap-6">
                <div class="bg-blue-600 text-white text-sm font-bold px-4 py-3 rounded-xl shadow-lg w-24 text-center">
                REMARK
                </div>
                <textarea
                    class="flex-1 p-4 border-2 border-gray-400 rounded-xl text-sm resize-none focus:border-blue-400 "
                    rows="2"
                    placeholder="Additional comments..."
                ></textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="p-8">
            <button class="w-full py-4 bg-gradient-to-r bg-black text-white text-base font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 focus:outline-none">
                Submit Feedback
            </button>
        </div>
    </div>
</body>

</html>

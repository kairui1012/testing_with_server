<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Simple Feedback List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-50">
    @include('layouts.navigation')
    <div class="max-w-[80%] mx-auto">
        <h1 class="text-2xl font-bold mb-6 mt-6">Feedback</h1>
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden ">
        <!-- Good Feedback -->
        <div class="p-8 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
            <div class="flex items-center gap-6">
                <div class="bg-gradient-to-r from-green-400 to-green-600 text-white text-sm font-bold px-4 py-3 rounded-xl shadow-lg w-20 text-center">
                    GOOD
                </div>
                <textarea
                    class="flex-1 p-4 border-2 border-green-200 rounded-xl text-sm resize-none focus:outline-none focus:border-green-400 focus:ring-4 focus:ring-green-100 transition-all duration-200 hover:border-green-300"
                    rows="3"
                    placeholder="Share what went well..."
                ></textarea>
            </div>
        </div>

        <!-- Bad Feedback -->
        <div class="p-8 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
            <div class="flex items-center gap-6">
                <div class="bg-gradient-to-r from-red-400 to-red-600 text-white text-sm font-bold px-4 py-3 rounded-xl shadow-lg w-20 text-center">
                    BAD
                </div>
                <textarea
                    class="flex-1 p-4 border-2 border-red-200 rounded-xl text-sm resize-none focus:outline-none focus:border-red-400 focus:ring-4 focus:ring-red-100 transition-all duration-200 hover:border-red-300"
                    rows="3"
                    placeholder="What could be improved..."
                ></textarea>
            </div>
        </div>

        <!-- Remark -->
        <div class="p-8 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
            <div class="flex items-center gap-6">
                <div class="bg-gradient-to-r from-blue-400 to-blue-600 text-white text-sm font-bold px-4 py-3 rounded-xl shadow-lg w-20 text-center">
                    REMARK
                </div>
                <textarea
                    class="flex-1 p-4 border-2 border-blue-200 rounded-xl text-sm resize-none focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all duration-200 hover:border-blue-300"
                    rows="3"
                    placeholder="Additional comments..."
                ></textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="p-8">
            <button class="w-full py-4 bg-gradient-to-r bg-black text-white text-base font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-purple-300">
                Submit Feedback
            </button>
        </div>
    </div>
</body>

</html>

@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Weekly Report') }}
    </h2>
@endsection

@section('content')
    <div class="md:py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('feedbacks.submit') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Good Feedback Section -->
                        <div class="border-l-4 border-green-500 bg-green-50 dark:bg-green-900/20 p-6 rounded-r-lg">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">Good Feedback</h3>
                            <textarea
                                name="good"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                placeholder="Share positive feedback, achievements, and things that went well this week..."
                            >{{ old('good') }}</textarea>
                        </div>

                        <!-- Bad Feedback Section -->
                        <div class="border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 p-6 rounded-r-lg">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">Bad Feedback</h3>
                            <textarea
                                name="bad"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                placeholder="Describe challenges, issues, or areas that need improvement..."
                            >{{ old('bad') }}</textarea>
                        </div>

                        <!-- Referrer Section -->
                        <div class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 p-6 rounded-r-lg">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">Referrer</h3>
                            <input
                                type="text"
                                name="referrer"
                                value="{{ old('referrer') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                placeholder="Who referred this feedback or where did it come from?"
                            />
                        </div>

                        <!-- Remark Section -->
                        <div class="border-l-4 border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-r-lg">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">Remark</h3>
                            <input
                                type="text"
                                name="remark"
                                value="{{ old('remark') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                placeholder="Any additional remarks or notes..."
                            />
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-6">
                            <button
                                type="submit"
                                class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                            >
                                Submit Weekly Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection



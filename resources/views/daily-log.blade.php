@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Daily Log') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Daily Mission Requirements Section -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Daily Mission Requirements</h3>
                        <div class="space-y-4">
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="open_enjoy_app"
                                    class="h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:bg-gray-700"
                                >
                                <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">OPEN "ENJOY" APP</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="check_in"
                                    class="h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:bg-gray-700"
                                >
                                <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">CHECK IN</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="play_view_video"
                                    class="h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:bg-gray-700"
                                >
                                <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">PLAY/VIEW VIDEO</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toDateString();
            const checkboxes = ['open_enjoy_app', 'check_in', 'play_view_video'];
            
            // Check if it's a new day and clear old data
            const lastSavedDate = localStorage.getItem('daily_log_date');
            if (lastSavedDate !== today) {
                // New day - clear all saved states
                checkboxes.forEach(id => {
                    localStorage.removeItem(`daily_log_${id}`);
                });
                localStorage.setItem('daily_log_date', today);
            }
            
            // Restore checkbox states from localStorage
            checkboxes.forEach(id => {
                const checkbox = document.getElementById(id);
                const savedState = localStorage.getItem(`daily_log_${id}`);
                if (savedState === 'true') {
                    checkbox.checked = true;
                }
                
                // Add event listener to save state when changed
                checkbox.addEventListener('change', function() {
                    localStorage.setItem(`daily_log_${id}`, this.checked);
                });
            });
        });
    </script>

@endsection


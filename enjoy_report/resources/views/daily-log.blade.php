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
                                    {{ $dailyLog->open_enjoy_app ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:bg-gray-700"
                                >
                                <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">OPEN "ENJOY" APP</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="check_in"
                                    {{ $dailyLog->check_in ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:bg-gray-700"
                                >
                                <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">CHECK IN</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="play_view_video"
                                    {{ $dailyLog->play_view_video ? 'checked' : '' }}
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
            const checkboxes = ['open_enjoy_app', 'check_in', 'play_view_video'];
            
            checkboxes.forEach(id => {
                const checkbox = document.getElementById(id);
                
                checkbox.addEventListener('change', function() {
                    // Send AJAX request to update the database
                    fetch('{{ route("daily-log.update") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            field: id,
                            value: this.checked
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            // If update failed, revert the checkbox
                            this.checked = !this.checked;
                            alert('Failed to update daily log. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revert the checkbox on error
                        this.checked = !this.checked;
                        alert('An error occurred. Please try again.');
                    });
                });
            });
        });
    </script>

@endsection


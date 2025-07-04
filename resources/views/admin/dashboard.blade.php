<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Feedback Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="text-gray-600 mt-2">Manage feedback submissions and weekly reports</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Feedbacks</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalFeedbacks }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">This Week</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $thisWeekFeedbacks }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ App\Models\User::count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-2.863">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Weeks Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <!-- Header with Filter -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">Recent Weeks</h2>
                <div class="flex items-center space-x-4">
                    <!-- Filter Dropdown -->
                    <div class="relative">
                        <form method="GET" action="{{ route('admin.filter') }}" id="filterForm">
                            <select name="week" onchange="document.getElementById('filterForm').submit()"
                                class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-64">
                                <option value="all" {{ $selectedWeek==='all' ? 'selected' : '' }}>See All Weeks</option>
                                @foreach($weeks as $week)
                                <option value="{{ $week['value'] }}" {{ $selectedWeek===$week['value'] ? 'selected' : ''
                                    }}>
                                    {{ $week['label'] }}
                                </option>
                                @endforeach
                            </select>
                            <!-- Dropdown Arrow -->
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-left">
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Good</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Bad</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Referrer
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Remark</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Week</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($feedbacks as $feedback)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $feedback->phone }}</div>
                                        <!-- Removed week display here -->
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    @if($feedback->good)
                                    <div class="max-w-xs break-words" title="{{ $feedback->good }}">
                                        {{ Str::limit($feedback->good, 100) }}
                                    </div>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    @if($feedback->bad)
                                    <div class="max-w-xs break-words" title="{{ $feedback->bad }}">
                                        {{ Str::limit($feedback->bad, 100) }}
                                    </div>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    @if($feedback->referrer)
                                    <div class="break-words">{{ $feedback->referrer }}</div>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    @if($feedback->remark)
                                    <div class="max-w-xs break-words" title="{{ $feedback->remark }}">
                                        {{ Str::limit($feedback->remark, 80) }}
                                    </div>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $feedback->week_range ?? $feedback->week }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $feedback->created_at->format('M d, Y') }}
                                <div class="text-xs text-gray-400">{{ $feedback->created_at->format('H:i') }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No feedback found</h3>
                                    <p class="mt-1 text-sm text-gray-500">No feedback submissions match your current
                                        filter.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($feedbacks->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $feedbacks->links() }}
            </div>
            @endif
        </div>
    </div>

    <script>
        // Auto-submit form when filter changes
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filterForm');
            const weekSelect = filterForm.querySelector('select[name="week"]');

            weekSelect.addEventListener('change', function() {
                filterForm.submit();
            });
        });
    </script>
</body>

</html>

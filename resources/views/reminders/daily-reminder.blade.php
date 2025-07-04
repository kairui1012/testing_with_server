<!-- resources/views/reminders/daily-reminder.blade.php -->
@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Daily WhatsApp Reminders') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-400 p-3 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reminder.store') }}">
                        @csrf
                        <div class="space-y-6">
                            @foreach ($weekdays as $dayNumber => $dayName)
                                @php
                                    $reminder = $reminders->get($dayNumber);
                                @endphp
                                <div class="p-4 border rounded-lg">
                                    <h3 class="text-lg font-bold">{{ $dayName }}</h3>
                                    <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                        <!-- Time Input -->
                                        <div>
                                            <label for="time_{{ $dayNumber }}" class="block font-medium text-sm text-gray-700">Time</label>
                                            <input type="time" name="reminders[{{ $dayNumber }}][time]" id="time_{{ $dayNumber }}" value="{{ optional($reminder)->time }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </div>

                                        <!-- Message Textarea -->
                                        <div class="md:col-span-2">
                                            <label for="message_{{ $dayNumber }}" class="block font-medium text-sm text-gray-700">Custom Message (Optional)</label>
                                            <textarea name="reminders[{{ $dayNumber }}][message]" id="message_{{ $dayNumber }}" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="If empty, a default message will be sent.">{{ optional($reminder)->message }}</textarea>
                                        </div>

                                        <!-- Active Checkbox -->
                                        <div class="md:col-span-3">
                                            <label for="is_active_{{ $dayNumber }}" class="flex items-center">
                                                <input type="checkbox" name="reminders[{{ $dayNumber }}][is_active]" id="is_active_{{ $dayNumber }}" value="1" @if(optional($reminder)->is_active ?? false) checked @endif class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-600">Enable reminder for {{ $dayName }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Save Settings
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

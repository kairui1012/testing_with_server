
@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Feedback Histories') }}
    </h2>
@endsection

@section('content')
    <div class="w-10/12 py-12 md:w-full mx-auto min-h-screen overflow-hidden">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @livewire('show-feedbacks')
            </div>
        </div>
    </div>
@endsection

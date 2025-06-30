<?php
use App\Livewire\Counter;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbacksController;


Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/login', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/daily-Log', function () {
    return view('daily-log');
})->middleware(['auth', 'verified'])->name('daily-Log');

Route::get('/weekly-report', function () {
    return view('weekly-report');
})->middleware(['auth', 'verified'])->name('weekly-report');

Route::get('/feedback-history', function () {
    return view('feedback-history');
})->middleware(['auth', 'verified'])->name('feedback-history');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/feedback', [FeedbacksController::class, 'submitFeedback'])->name('feedbacks.submit');
require __DIR__.'/auth.php';

Route::get('/feedbacks', function () {
    return view('feedbacks.show');
});

Route::get('/counter', Counter::class);

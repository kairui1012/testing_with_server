<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/login', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/Daily-Log', function () {
    return view('Daily-Log');
})->middleware(['auth', 'verified'])->name('Daily-Log');

Route::get('/weekly-report', function () {
    return view('weekly-report');
})->middleware(['auth', 'verified'])->name('weekly-report');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/GoodFeedbackController', [FeedbackController::class, 'saveGood'])->name('good_feedback.submit');
Route::post('/BadFeedbackController', [FeedbackController::class, 'saveBad'])->name('bad_feedback.submit');

require __DIR__.'/auth.php';

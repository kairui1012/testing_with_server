<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbacksController;
use App\Http\Controllers\DailyLogController;
use App\Http\Controllers\AdminController;


Route::get('/', [DailyLogController::class, 'show'])->middleware(['auth', 'verified'])->name('daily-log');

Route::get('/login', function () {
    return view('login');
});


Route::get('/daily-Log', [DailyLogController::class, 'show'])->middleware(['auth', 'verified'])->name('daily-Log');

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

Route::post('/daily-log/update', [DailyLogController::class, 'update'])->middleware(['auth', 'verified'])->name('daily-log.update');

// Admin Routes
Route::get('/admin-dashboard-xyz123', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin-filter', [AdminController::class, 'filter'])->name('admin.filter');

require __DIR__.'/auth.php';

Route::get('/feedbacks', function () {
    return view('feedbacks.show');
});

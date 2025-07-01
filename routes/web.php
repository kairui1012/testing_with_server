<?php
use App\Livewire\Counter;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbacksController;
use App\Http\Controllers\DailyLogController;

Route::get('/', function () {
    return view('daily-log');
})->middleware(['auth', 'verified'])->name('daily-log');

Route::get('/login', function () {
    return view('login');
});


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
    // Daily log AJAX route
    Route::post('/daily-log/update', [DailyLogController::class, 'update'])->name('daily-log.update');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/weekly-report', [FeedbacksController::class, 'submitFeedback'])->name('weekly-report.submit');
Route::post('/daily-log', [FeedbacksController::class, 'submitFeedback'])->name('daily-log.submit');
Route::post('/feedbacks', [FeedbacksController::class, 'submitFeedback'])->name('feedbacks.submit');

// Admin Super URL Routes
Route::get('/admin-xyz123', [FeedbacksController::class, 'adminDashboard'])->name('admin.dashboard');
Route::get('/admin-xyz123/filter', [FeedbacksController::class, 'filterFeedbacks'])->name('admin.filter');

// Debug route to test mbstring
Route::get('/debug-admin', function() {
    return response()->json([
        'php_version' => PHP_VERSION,
        'mbstring_loaded' => extension_loaded('mbstring'),
        'mb_strimwidth_exists' => function_exists('mb_strimwidth'),
        'test_substr' => substr('Hello World Test String', 0, 10),
        'feedback_count' => App\Models\Feedback::count(),
    ]);
});

require __DIR__.'/auth.php';

Route::get('/feedbacks', function () {
    return view('feedbacks.show');
});

Route::get('/counter', Counter::class);

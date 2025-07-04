<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
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

Route::post('/feedback', [FeedbacksController::class, 'submitFeedback'])->name('feedbacks.submit');

Route::post('/daily-log/update', [DailyLogController::class, 'update'])->middleware(['auth', 'verified'])->name('daily-log.update');

// Admin Routes
Route::get('/admin-dashboard-xyz123', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin-filter', [AdminController::class, 'filter'])->name('admin.filter');

require __DIR__.'/auth.php';

Route::get('/feedbacks', function () {
    return view('feedbacks.show');
});

Route::get('/daily-reminder', function () {
    return view('reminder.index');
});


Route::middleware('auth')->group(function () {
    Route::get('/daily-reminder', [App\Http\Controllers\DailyReminderController::class, 'index'])->name('reminder.index');
    Route::post('/daily-reminder', [App\Http\Controllers\DailyReminderController::class, 'store'])->name('reminder.store');
});

Route::get('/hi', function () {
    $response = Http::post('http://165.22.240.183:3000/api/sendText', [
        'chatId' => '60102661019@c.us',
        'text' => 'Hi there!',
        'session' => 'default'
    ]);

    return response()->json([
        'status' => 'Message sent',
        'response' => $response->json()
    ]);
});

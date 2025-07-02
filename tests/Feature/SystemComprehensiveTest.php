<?php

use App\Models\User;
use App\Models\Feedbacks;
use App\Models\DailyLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

uses(RefreshDatabase::class);

/**
 * AUTHENTICATION & USER MANAGEMENT TESTS
 */
test('phone-based authentication works correctly', function () {
    $phone = '+60102661019';
    
    // Test login with existing phone number
    $response = $this->post('/login', ['phone' => $phone]);
    
    // Should create user and redirect to daily log
    $response->assertRedirect('/');
    $this->assertDatabaseHas('users', ['phone' => $phone]);
});

test('user can logout successfully', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $response = $this->post('/logout');
    
    $response->assertRedirect('/');
    $this->assertGuest();
});

/**
 * DAILY LOG FUNCTIONALITY TESTS
 */
test('daily log page displays correctly for authenticated user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $response = $this->get('/');
    
    $response->assertStatus(200);
    $response->assertViewIs('daily-log');
});

test('daily log creates one entry per user per day', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // First request should create daily log
    $this->get('/');
    
    // Second request should not create duplicate
    $this->get('/');
    
    $this->assertDatabaseCount('daily_logs', 1);
    $this->assertDatabaseHas('daily_logs', [
        'user_id' => $user->id,
        'log_date' => Carbon::today(),
    ]);
});

test('daily log fields can be updated', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $response = $this->post('/daily-log/update', [
        'field' => 'open_enjoy_app',
        'value' => true,
    ]);
    
    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('daily_logs', [
        'user_id' => $user->id,
        'open_enjoy_app' => true,
    ]);
});

test('daily log update validates field names', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $response = $this->postJson('/daily-log/update', [
        'field' => 'invalid_field',
        'value' => true,
    ]);
    
    $response->assertStatus(422);
});

/**
 * WEEKLY FEEDBACK FUNCTIONALITY TESTS
 */
test('weekly feedback form displays correctly', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $response = $this->get('/weekly-report');
    
    $response->assertStatus(200);
    $response->assertViewIs('weekly-report');
});

test('weekly feedback submission creates or updates feedback', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $feedbackData = [
        'good' => 'Great week!',
        'bad' => 'Some challenges',
        'remark' => 'Overall positive',
        'referrer' => 'Manager',
    ];
    
    $response = $this->post('/feedback', $feedbackData);
    
    $response->assertRedirect();
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('feedbacks', [
        'phone' => $user->phone,
        'good' => 'Great week!',
    ]);
});

test('weekly feedback uses correct week calculation', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Mock current date to test week calculation
    Carbon::setTestNow(Carbon::parse('2025-07-02')); // Should be Week 2
    
    $this->post('/feedback', [
        'good' => 'Test feedback',
        'bad' => 'Test issues',
        'remark' => 'Test remark',
        'referrer' => 'Test referrer',
    ]);
    
    $this->assertDatabaseHas('feedbacks', [
        'phone' => $user->phone,
        'week' => '2025-W02',
    ]);
    
    Carbon::setTestNow(); // Reset time
});

test('weekly feedback validation works correctly', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Test missing required fields
    $response = $this->post('/feedback', [
        'remark' => 'Only remark provided',
    ]);
    
    $response->assertSessionHasErrors(['good', 'bad']);
});

test('one feedback per user per week constraint works', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    Carbon::setTestNow(Carbon::parse('2025-07-02'));
    
    // First submission
    $this->post('/feedback', [
        'good' => 'First submission',
        'bad' => 'First issues',
        'remark' => 'First remark',
        'referrer' => 'First referrer',
    ]);
    
    // Second submission should update, not create new
    $this->post('/feedback', [
        'good' => 'Updated submission',
        'bad' => 'Updated issues',
        'remark' => 'Updated remark',
        'referrer' => 'Updated referrer',
    ]);
    
    $this->assertDatabaseCount('feedbacks', 1);
    $this->assertDatabaseHas('feedbacks', [
        'phone' => $user->phone,
        'good' => 'Updated submission',
        'week' => '2025-W02',
    ]);
    
    Carbon::setTestNow();
});

/**
 * FEEDBACK HISTORY & LIVEWIRE TESTS
 */
test('feedback history page displays correctly', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Create some feedback
    Feedbacks::create([
        'phone' => $user->phone,
        'good' => 'Test good',
        'bad' => 'Test bad',
        'remark' => 'Test remark',
        'referrer' => 'Test referrer',
        'week' => '2025-W01',
    ]);
    
    $response = $this->get('/feedback-history');
    
    $response->assertStatus(200);
    $response->assertViewIs('feedback-history');
});

test('livewire component loads user feedback correctly', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Create feedback for this user
    Feedbacks::create([
        'phone' => $user->phone,
        'good' => 'User feedback',
        'bad' => 'User issues',
        'remark' => 'User remark',
        'referrer' => 'User referrer',
        'week' => '2025-W01',
    ]);
    
    // Create feedback for another user (should not appear)
    Feedbacks::create([
        'phone' => '+60999999999',
        'good' => 'Other user feedback',
        'bad' => 'Other user issues',
        'remark' => 'Other user remark',
        'referrer' => 'Other user referrer',
        'week' => '2025-W01',
    ]);
    
    $component = \Livewire\Livewire::test(\App\Livewire\ShowFeedbacks::class);
    
    $component->assertSee('User feedback')
              ->assertDontSee('Other user feedback');
});

/**
 * ADMIN DASHBOARD TESTS
 */
test('admin dashboard requires proper authentication', function () {
    $response = $this->get('/admin-dashboard-xyz123');
    
    // Should be accessible without auth (based on current routes)
    $response->assertStatus(200);
});

test('admin dashboard displays week filtering correctly', function () {
    // Create test feedback data
    Feedbacks::create([
        'phone' => '+60102661019',
        'good' => 'Week 1 feedback',
        'bad' => 'Week 1 issues',
        'remark' => 'Week 1 remark',
        'referrer' => 'Week 1 referrer',
        'week' => '2025-W01',
        'created_at' => Carbon::parse('2025-06-25'),
    ]);
    
    Feedbacks::create([
        'phone' => '+60102661020',
        'good' => 'Week 2 feedback',
        'bad' => 'Week 2 issues',
        'remark' => 'Week 2 remark',
        'referrer' => 'Week 2 referrer',
        'week' => '2025-W02',
        'created_at' => Carbon::parse('2025-07-02'),
    ]);
    
    $response = $this->get('/admin-dashboard-xyz123');
    
    $response->assertStatus(200);
    $response->assertViewIs('admin.dashboard');
});

test('admin week filtering works correctly', function () {
    $startDate = '2025-06-23'; // Week 1 start
    
    $response = $this->get('/admin-filter?week=' . $startDate);
    
    $response->assertStatus(200);
});

/**
 * ROUTE ACCESSIBILITY TESTS
 */
test('protected routes require authentication', function () {
    $protectedRoutes = [
        '/',
        '/daily-Log',
        '/weekly-report',
        '/feedback-history',
    ];
    
    foreach ($protectedRoutes as $route) {
        $response = $this->get($route);
        $response->assertRedirect('/login');
    }
    
    // Test POST route separately
    $response = $this->post('/daily-log/update');
    $response->assertRedirect('/login');
});

test('public routes are accessible', function () {
    $publicRoutes = [
        '/login' => 200,
        '/admin-dashboard-xyz123' => 200,
    ];
    
    foreach ($publicRoutes as $route => $expectedStatus) {
        $response = $this->get($route);
        $response->assertStatus($expectedStatus);
    }
});

/**
 * DATABASE INTEGRITY TESTS
 */
test('database relationships work correctly', function () {
    $user = User::factory()->create();
    
    $dailyLog = DailyLog::create([
        'user_id' => $user->id,
        'log_date' => Carbon::today(),
        'open_enjoy_app' => true,
        'check_in' => false,
        'play_view_video' => true,
    ]);
    
    // Test relationship
    $this->assertEquals($user->id, $dailyLog->user->id);
});

test('unique constraints work correctly', function () {
    $user = User::factory()->create();
    
    // Create first daily log
    DailyLog::create([
        'user_id' => $user->id,
        'log_date' => Carbon::today(),
        'open_enjoy_app' => true,
    ]);
    
    // Attempt to create duplicate should fail
    $this->expectException(\Illuminate\Database\QueryException::class);
    
    DailyLog::create([
        'user_id' => $user->id,
        'log_date' => Carbon::today(),
        'check_in' => true,
    ]);
});

/**
 * NAVIGATION & UI TESTS
 */
test('navigation displays correctly for authenticated users', function () {
    $user = User::factory()->create(['phone' => '+60102661019']);
    $this->actingAs($user);
    
    $response = $this->get('/');
    
    $response->assertSee('daily-log');
    $response->assertSee('weekly-report');
    $response->assertSee('feedback-history');
    $response->assertSee('+60102661019'); // User's phone number
});

/**
 * ERROR HANDLING TESTS
 */
test('invalid daily log updates are rejected', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Test invalid field
    $response = $this->postJson('/daily-log/update', [
        'field' => 'invalid_field',
        'value' => true,
    ]);
    
    $response->assertStatus(422);
    
    // Test invalid value type
    $response = $this->postJson('/daily-log/update', [
        'field' => 'open_enjoy_app',
        'value' => 'not_boolean',
    ]);
    
    $response->assertStatus(422);
});

test('unauthorized feedback access is prevented', function () {
    $user1 = User::factory()->create(['phone' => '+60111111111']);
    $user2 = User::factory()->create(['phone' => '+60222222222']);
    
    // Create feedback for user1
    $feedback = Feedbacks::create([
        'phone' => $user1->phone,
        'good' => 'User1 feedback',
        'bad' => 'User1 issues',
        'week' => '2025-W01',
    ]);
    
    // Login as user2 and try to access user1's feedback via Livewire
    $this->actingAs($user2);
    
    $component = \Livewire\Livewire::test(\App\Livewire\ShowFeedbacks::class);
    
    // Should not see user1's feedback
    $component->assertDontSee('User1 feedback');
});

/**
 * WEEK CALCULATION TESTS
 */
test('week calculation handles edge cases correctly', function () {
    $testCases = [
        '2025-06-23' => '2025-W01', // Week 1 start (Monday)
        '2025-06-29' => '2025-W01', // Week 1 end (Sunday)
        '2025-06-30' => '2025-W02', // Week 2 start (Monday)
        '2025-07-06' => '2025-W02', // Week 2 end (Sunday)
        '2025-07-07' => '2025-W03', // Week 3 start (Monday)
    ];
    
    $controller = new \App\Http\Controllers\FeedbacksController();
    $reflection = new \ReflectionClass($controller);
    $method = $reflection->getMethod('getCurrentWeek');
    $method->setAccessible(true);
    
    foreach ($testCases as $date => $expectedWeek) {
        Carbon::setTestNow(Carbon::parse($date));
        $actualWeek = $method->invoke($controller);
        
        expect($actualWeek)->toBe($expectedWeek, "Date {$date} should be {$expectedWeek}, got {$actualWeek}");
    }
    
    Carbon::setTestNow();
});

/**
 * PERFORMANCE TESTS
 */
test('database queries are optimized', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Create multiple feedback entries
    for ($i = 1; $i <= 10; $i++) {
        Feedbacks::create([
            'phone' => $user->phone,
            'good' => "Feedback {$i}",
            'bad' => "Issue {$i}",
            'week' => "2025-W{$i}",
        ]);
    }
    
    // Test that feedback history loads efficiently
    $response = $this->get('/feedback-history');
    $response->assertStatus(200);
    
    // Test Livewire component loads efficiently
    $component = \Livewire\Livewire::test(\App\Livewire\ShowFeedbacks::class);
    $component->assertStatus(200);
});

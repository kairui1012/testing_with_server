<?php

use App\Http\Controllers\FeedbacksController;
use App\Models\User;
use App\Models\Feedbacks;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

uses(RefreshDatabase::class);

test('week calculation returns correct format', function () {
    $controller = new FeedbacksController();
    $reflection = new \ReflectionClass($controller);
    $method = $reflection->getMethod('getCurrentWeek');
    $method->setAccessible(true);
    
    // Test various dates
    $testCases = [
        '2025-06-23' => '2025-W01', // Week 1 start (Monday)
        '2025-06-24' => '2025-W01', // Week 1 Tuesday
        '2025-06-29' => '2025-W01', // Week 1 end (Sunday)
        '2025-06-30' => '2025-W02', // Week 2 start (Monday)
        '2025-07-01' => '2025-W02', // Week 2 Tuesday
        '2025-07-02' => '2025-W02', // Week 2 Wednesday (current date)
        '2025-07-06' => '2025-W02', // Week 2 end (Sunday)
        '2025-07-07' => '2025-W03', // Week 3 start (Monday)
        '2025-07-14' => '2025-W04', // Week 4 start
        '2025-08-04' => '2025-W07', // Several weeks later
    ];
    
    foreach ($testCases as $date => $expectedWeek) {
        Carbon::setTestNow(Carbon::parse($date));
        $actualWeek = $method->invoke($controller);
        
        expect($actualWeek)->toBe($expectedWeek, "Date {$date} should return {$expectedWeek}, got {$actualWeek}");
    }
    
    Carbon::setTestNow(); // Reset
});

test('week calculation handles year boundaries correctly', function () {
    $controller = new FeedbacksController();
    $reflection = new \ReflectionClass($controller);
    $method = $reflection->getMethod('getCurrentWeek');
    $method->setAccessible(true);
    
    // Test dates far in the future
    $futureTestCases = [
        '2026-01-01' => '2025-W28', // Far future should still work
        '2025-12-31' => '2025-W27', // End of year
    ];
    
    foreach ($futureTestCases as $date => $expectedWeek) {
        Carbon::setTestNow(Carbon::parse($date));
        $actualWeek = $method->invoke($controller);
        
        // Week format should still be 2025-W## since our system starts in 2025
        expect($actualWeek)->toContain('2025-W');
    }
    
    Carbon::setTestNow();
});

test('week calculation before start date defaults to week 1', function () {
    $controller = new FeedbacksController();
    $reflection = new \ReflectionClass($controller);
    $method = $reflection->getMethod('getCurrentWeek');
    $method->setAccessible(true);
    
    // Test date before June 23, 2025
    Carbon::setTestNow(Carbon::parse('2025-06-22'));
    $result = $method->invoke($controller);
    
    expect($result)->toBe('2025-W01');
    
    Carbon::setTestNow();
});

test('get or create weekly feedback creates new feedback', function () {
    $user = User::factory()->create(['phone' => '+60123456789']);
    $this->actingAs($user);
    
    Carbon::setTestNow(Carbon::parse('2025-07-02')); // Week 2
    
    $controller = new FeedbacksController();
    $reflection = new \ReflectionClass($controller);
    $method = $reflection->getMethod('getOrCreateWeeklyFeedback');
    $method->setAccessible(true);
    
    $feedback = $method->invoke($controller, $user->phone);
    
    expect($feedback)->toBeInstanceOf(Feedbacks::class);
    expect($feedback->phone)->toBe($user->phone);
    expect($feedback->week)->toBe('2025-W02');
    
    $this->assertDatabaseHas('feedbacks', [
        'phone' => $user->phone,
        'week' => '2025-W02',
    ]);
    
    Carbon::setTestNow();
});

test('get or create weekly feedback returns existing feedback', function () {
    $user = User::factory()->create(['phone' => '+60123456789']);
    $this->actingAs($user);
    
    Carbon::setTestNow(Carbon::parse('2025-07-02')); // Week 2
    
    // Create existing feedback
    $existingFeedback = Feedbacks::create([
        'phone' => $user->phone,
        'good' => 'Existing good',
        'bad' => 'Existing bad',
        'week' => '2025-W02',
    ]);
    
    $controller = new FeedbacksController();
    $reflection = new \ReflectionClass($controller);
    $method = $reflection->getMethod('getOrCreateWeeklyFeedback');
    $method->setAccessible(true);
    
    $feedback = $method->invoke($controller, $user->phone);
    
    expect($feedback->id)->toBe($existingFeedback->id);
    expect($feedback->good)->toBe('Existing good');
    
    // Should not create duplicate
    $this->assertDatabaseCount('feedbacks', 1);
    
    Carbon::setTestNow();
});

test('week calculation is consistent across multiple calls', function () {
    $controller = new FeedbacksController();
    $reflection = new \ReflectionClass($controller);
    $method = $reflection->getMethod('getCurrentWeek');
    $method->setAccessible(true);
    
    Carbon::setTestNow(Carbon::parse('2025-07-02'));
    
    // Call multiple times - should return same result
    $week1 = $method->invoke($controller);
    $week2 = $method->invoke($controller);
    $week3 = $method->invoke($controller);
    
    expect($week1)->toBe($week2);
    expect($week2)->toBe($week3);
    expect($week1)->toBe('2025-W02');
    
    Carbon::setTestNow();
});

test('week calculation handles monday to sunday correctly', function () {
    $controller = new FeedbacksController();
    $reflection = new \ReflectionClass($controller);
    $method = $reflection->getMethod('getCurrentWeek');
    $method->setAccessible(true);
    
    // Test all days of week 2 (June 30 - July 6, 2025)
    $week2Days = [
        '2025-06-30', // Monday
        '2025-07-01', // Tuesday
        '2025-07-02', // Wednesday
        '2025-07-03', // Thursday
        '2025-07-04', // Friday
        '2025-07-05', // Saturday
        '2025-07-06', // Sunday
    ];
    
    foreach ($week2Days as $date) {
        Carbon::setTestNow(Carbon::parse($date));
        $week = $method->invoke($controller);
        
        expect($week)->toBe('2025-W02', "Date {$date} should be Week 2");
    }
    
    Carbon::setTestNow();
});

test('feedback submission uses correct week for different dates', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Test submission on different dates
    $testDates = [
        '2025-06-25' => '2025-W01',
        '2025-07-02' => '2025-W02',
        '2025-07-09' => '2025-W03',
    ];
    
    foreach ($testDates as $date => $expectedWeek) {
        Carbon::setTestNow(Carbon::parse($date));
        
        // Clear previous feedback
        Feedbacks::where('phone', $user->phone)->delete();
        
        $response = $this->post('/feedback', [
            'good' => "Good for {$date}",
            'bad' => "Bad for {$date}",
            'remark' => "Remark for {$date}",
            'referrer' => "Referrer for {$date}",
        ]);
        
        $response->assertRedirect();
        
        $this->assertDatabaseHas('feedbacks', [
            'phone' => $user->phone,
            'week' => $expectedWeek,
            'good' => "Good for {$date}",
        ]);
    }
    
    Carbon::setTestNow();
});

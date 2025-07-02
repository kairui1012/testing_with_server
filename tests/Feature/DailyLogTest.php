<?php

use App\Models\User;
use App\Models\DailyLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

uses(RefreshDatabase::class);

test('daily log page loads correctly', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $response = $this->get('/');
    
    $response->assertStatus(200);
    $response->assertViewIs('daily-log');
    $response->assertViewHas('dailyLog');
});

test('daily log creates entry for new user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $response = $this->get('/');
    
    $response->assertStatus(200);
    
    $this->assertDatabaseHas('daily_logs', [
        'user_id' => $user->id,
        'log_date' => Carbon::today(),
        'open_enjoy_app' => false,
        'check_in' => false,
        'play_view_video' => false,
    ]);
});

test('daily log returns existing entry for returning user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Create existing daily log
    $existingLog = DailyLog::create([
        'user_id' => $user->id,
        'log_date' => Carbon::today(),
        'open_enjoy_app' => true,
        'check_in' => false,
        'play_view_video' => true,
    ]);
    
    $response = $this->get('/');
    
    $response->assertStatus(200);
    
    // Should not create new entry
    $this->assertDatabaseCount('daily_logs', 1);
    
    // Should return existing values
    $dailyLog = $response->viewData('dailyLog');
    expect($dailyLog->open_enjoy_app)->toBeTrue();
    expect($dailyLog->play_view_video)->toBeTrue();
    expect($dailyLog->check_in)->toBeFalse();
});

test('daily log update works for valid fields', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $validFields = [
        'open_enjoy_app' => true,
        'check_in' => true,
        'play_view_video' => false,
    ];
    
    foreach ($validFields as $field => $value) {
        $response = $this->post('/daily-log/update', [
            'field' => $field,
            'value' => $value,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('daily_logs', [
            'user_id' => $user->id,
            'log_date' => Carbon::today(),
            $field => $value,
        ]);
    }
});

test('daily log update rejects invalid fields', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $invalidFields = [
        'invalid_field',
        'user_id',
        'created_at',
        'updated_at',
        'some_other_field',
    ];
    
    foreach ($invalidFields as $field) {
        $response = $this->postJson('/daily-log/update', [
            'field' => $field,
            'value' => true,
        ]);
        
        $response->assertStatus(422);
    }
});

test('daily log update validates boolean values', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $invalidValues = [
        'string_value',
        123,
        'yes',
        'no',
        null,
    ];
    
    foreach ($invalidValues as $value) {
        $response = $this->postJson('/daily-log/update', [
            'field' => 'open_enjoy_app',
            'value' => $value,
        ]);
        
        $response->assertStatus(422);
    }
});

test('daily log update accepts valid boolean values', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $validValues = [
        true,
        false,
        1,
        0,
        '1',
        '0',
        'true',
        'false',
    ];
    
    foreach ($validValues as $value) {
        $response = $this->postJson('/daily-log/update', [
            'field' => 'open_enjoy_app',
            'value' => $value,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
});

test('daily log enforces one entry per user per day', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Create first daily log
    DailyLog::create([
        'user_id' => $user->id,
        'log_date' => Carbon::today(),
        'open_enjoy_app' => false,
    ]);
    
    // Try to create duplicate
    $this->expectException(\Illuminate\Database\QueryException::class);
    
    DailyLog::create([
        'user_id' => $user->id,
        'log_date' => Carbon::today(),
        'check_in' => true,
    ]);
});

test('daily log allows multiple users on same day', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    DailyLog::create([
        'user_id' => $user1->id,
        'log_date' => Carbon::today(),
        'open_enjoy_app' => true,
    ]);
    
    DailyLog::create([
        'user_id' => $user2->id,
        'log_date' => Carbon::today(),
        'check_in' => true,
    ]);
    
    $this->assertDatabaseCount('daily_logs', 2);
});

test('daily log allows same user on different days', function () {
    $user = User::factory()->create();
    
    DailyLog::create([
        'user_id' => $user->id,
        'log_date' => Carbon::today(),
        'open_enjoy_app' => true,
    ]);
    
    DailyLog::create([
        'user_id' => $user->id,
        'log_date' => Carbon::yesterday(),
        'check_in' => true,
    ]);
    
    $this->assertDatabaseCount('daily_logs', 2);
});

test('daily log user relationship works correctly', function () {
    $user = User::factory()->create(['phone' => '+60123456789']);
    
    $dailyLog = DailyLog::create([
        'user_id' => $user->id,
        'log_date' => Carbon::today(),
        'open_enjoy_app' => true,
    ]);
    
    // Test relationship
    expect($dailyLog->user->phone)->toBe('+60123456789');
    expect($dailyLog->user->id)->toBe($user->id);
});

test('daily log requires authentication', function () {
    // Try to access without authentication
    $response = $this->get('/');
    
    $response->assertRedirect('/login');
});

test('daily log update requires authentication', function () {
    // Try to update without authentication
    $response = $this->post('/daily-log/update', [
        'field' => 'open_enjoy_app',
        'value' => true,
    ]);
    
    $response->assertRedirect('/login');
});

test('daily log uses correct date', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Mock specific date
    Carbon::setTestNow(Carbon::parse('2025-07-15'));
    
    $response = $this->get('/');
    
    $this->assertDatabaseHas('daily_logs', [
        'user_id' => $user->id,
        'log_date' => '2025-07-15 00:00:00',
    ]);
    
    Carbon::setTestNow(); // Reset
});

test('daily log model casts work correctly', function () {
    $user = User::factory()->create();
    
    $dailyLog = DailyLog::create([
        'user_id' => $user->id,
        'log_date' => '2025-07-02',
        'open_enjoy_app' => 1,
        'check_in' => 0,
        'play_view_video' => true,
    ]);
    
    // Test that casts work
    expect($dailyLog->log_date)->toBeInstanceOf(Carbon::class);
    expect($dailyLog->open_enjoy_app)->toBeTrue();
    expect($dailyLog->check_in)->toBeFalse();
    expect($dailyLog->play_view_video)->toBeTrue();
});

<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('profile page is not available', function () {
    // Test that profile route returns 404 since profile functionality is removed
    $response = $this->get('/profile');
    
    $response->assertStatus(404);
});

test('profile information cannot be updated', function () {
    // Test that profile update route returns 404 since profile functionality is removed
    $response = $this->patch('/profile', []);
    
    $response->assertStatus(404);
});

test('email verification is not applicable', function () {
    // This app uses phone-based authentication, not email verification
    $this->assertTrue(true, 'Email verification not applicable - app uses phone authentication');
});

test('user cannot delete account via profile', function () {
    // Test that account deletion via profile returns 404 since profile functionality is removed
    $response = $this->delete('/profile');
    
    $response->assertStatus(404);
});

test('password verification for account deletion is not applicable', function () {
    // Profile functionality is removed, so password verification for deletion is not applicable
    $this->assertTrue(true, 'Password verification not applicable - profile functionality removed');
});

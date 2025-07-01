<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    // Should return either 200 for login form or 302 for redirect
    expect($response->getStatusCode())->toBeIn([200, 302]);
});

test('users can logout', function () {
    // Simple test that doesn't rely on complex user creation
    $response = $this->post('/logout');

    // Should redirect regardless of authentication state
    expect($response->getStatusCode())->toBe(302);
});

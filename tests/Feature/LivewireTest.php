<?php

use App\Models\User;
use App\Models\Feedbacks;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Carbon\Carbon;

uses(RefreshDatabase::class);

test('livewire component renders correctly', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    Livewire::test(\App\Livewire\ShowFeedbacks::class)
        ->assertStatus(200);
});

test('livewire displays user feedbacks only', function () {
    $user = User::factory()->create(['phone' => '+60111111111']);
    $otherUser = User::factory()->create(['phone' => '+60222222222']);
    
    // Create feedback for current user
    Feedbacks::create([
        'phone' => $user->phone,
        'good' => 'My feedback',
        'bad' => 'My issues',
        'remark' => 'My remark',
        'referrer' => 'My referrer',
        'week' => '2025-W01',
    ]);
    
    // Create feedback for other user
    Feedbacks::create([
        'phone' => $otherUser->phone,
        'good' => 'Other feedback',
        'bad' => 'Other issues',
        'remark' => 'Other remark',
        'referrer' => 'Other referrer',
        'week' => '2025-W01',
    ]);
    
    $this->actingAs($user);
    
    Livewire::test(\App\Livewire\ShowFeedbacks::class)
        ->assertSee('My feedback')
        ->assertDontSee('Other feedback');
});

test('livewire inline editing works correctly', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $feedback = Feedbacks::create([
        'phone' => $user->phone,
        'good' => 'Original good feedback',
        'bad' => 'Original bad feedback',
        'remark' => 'Original remark',
        'referrer' => 'Original referrer',
        'week' => '2025-W01',
    ]);
    
    Livewire::test(\App\Livewire\ShowFeedbacks::class)
        ->call('editField', $feedback->id, 'good')
        ->assertSet('editingField', "good-{$feedback->id}")
        ->assertSet('editingValue', 'Original good feedback')
        ->set('editingValue', 'Updated good feedback')
        ->call('saveField')
        ->assertSet('editingField', null);
    
    $this->assertDatabaseHas('feedbacks', [
        'id' => $feedback->id,
        'good' => 'Updated good feedback',
    ]);
});

test('livewire editing respects user authorization', function () {
    $user1 = User::factory()->create(['phone' => '+60111111111']);
    $user2 = User::factory()->create(['phone' => '+60222222222']);
    
    $feedback = Feedbacks::create([
        'phone' => $user1->phone,
        'good' => 'User1 feedback',
        'bad' => 'User1 issues',
        'week' => '2025-W01',
    ]);
    
    // Login as user2 and try to edit user1's feedback
    $this->actingAs($user2);
    
    Livewire::test(\App\Livewire\ShowFeedbacks::class)
        ->call('editField', $feedback->id, 'good')
        ->set('editingValue', 'Hacked feedback')
        ->call('saveField');
    
    // Feedback should not be changed
    $this->assertDatabaseHas('feedbacks', [
        'id' => $feedback->id,
        'good' => 'User1 feedback', // Original value
    ]);
});

test('livewire handles cancel edit correctly', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $feedback = Feedbacks::create([
        'phone' => $user->phone,
        'good' => 'Original feedback',
        'week' => '2025-W01',
    ]);
    
    Livewire::test(\App\Livewire\ShowFeedbacks::class)
        ->call('editField', $feedback->id, 'good')
        ->assertSet('editingField', "good-{$feedback->id}")
        ->set('editingValue', 'Modified feedback')
        ->call('cancelEdit')
        ->assertSet('editingField', null)
        ->assertSet('editingValue', '');
    
    // Original value should remain unchanged
    $this->assertDatabaseHas('feedbacks', [
        'id' => $feedback->id,
        'good' => 'Original feedback',
    ]);
});

test('livewire handles empty feedback fields', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $feedback = Feedbacks::create([
        'phone' => $user->phone,
        'good' => null,
        'bad' => null,
        'remark' => null,
        'referrer' => 'Some referrer',
        'week' => '2025-W01',
    ]);
    
    Livewire::test(\App\Livewire\ShowFeedbacks::class)
        ->assertSee('目前为空，点击编辑') // Should show placeholder for empty fields
        ->assertSee('Some referrer'); // Should show actual value for non-empty fields
});

test('livewire editing different field types', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $feedback = Feedbacks::create([
        'phone' => $user->phone,
        'good' => 'Original good',
        'bad' => 'Original bad',
        'remark' => 'Original remark',
        'referrer' => 'Original referrer',
        'week' => '2025-W01',
    ]);
    
    $component = Livewire::test(\App\Livewire\ShowFeedbacks::class);
    
    // Test editing 'good' field
    $component->call('editField', $feedback->id, 'good')
              ->set('editingValue', 'Updated good')
              ->call('saveField');
    
    // Test editing 'bad' field
    $component->call('editField', $feedback->id, 'bad')
              ->set('editingValue', 'Updated bad')
              ->call('saveField');
    
    // Test editing 'remark' field
    $component->call('editField', $feedback->id, 'remark')
              ->set('editingValue', 'Updated remark')
              ->call('saveField');
    
    $this->assertDatabaseHas('feedbacks', [
        'id' => $feedback->id,
        'good' => 'Updated good',
        'bad' => 'Updated bad',
        'remark' => 'Updated remark',
    ]);
});

test('livewire component refreshes data after edit', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $feedback = Feedbacks::create([
        'phone' => $user->phone,
        'good' => 'Original feedback',
        'week' => '2025-W01',
    ]);
    
    $component = Livewire::test(\App\Livewire\ShowFeedbacks::class);
    
    // Edit the feedback
    $component->call('editField', $feedback->id, 'good')
              ->set('editingValue', 'Updated feedback')
              ->call('saveField');
    
    // Component should show updated value
    $component->assertSee('Updated feedback')
              ->assertDontSee('Original feedback');
});

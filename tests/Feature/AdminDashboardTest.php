<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Feedback;
use App\Http\Controllers\FeedbacksController;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create some test feedback data with custom week format
        $currentWeek = $this->getCurrentCustomWeek();
        
        Feedback::create([
            'phone' => '+60123456789',
            'good' => 'Test good feedback',
            'bad' => 'Test bad feedback',
            'remark' => 'Test remark',
            'referrer' => 'Test Referrer',
            'week' => $currentWeek, // Use current week
        ]);
        
        Feedback::create([
            'phone' => '+60987654321',
            'good' => 'Another good feedback',
            'bad' => 'Another bad feedback',
            'remark' => null,
            'referrer' => null,
            'week' => $currentWeek, // Use current week
        ]);
    }

    private function getCurrentCustomWeek()
    {
        // Base date: June 23, 2025 is Week 1
        $baseTimestamp = mktime(0, 0, 0, 6, 23, 2025); // June 23, 2025
        $currentTimestamp = time(); // Current timestamp
        
        // If current time is before base date, default to Week 1
        if ($currentTimestamp < $baseTimestamp) {
            return "2025-W01";
        }
        
        // Calculate days since base date
        $daysDiff = floor(($currentTimestamp - $baseTimestamp) / (24 * 60 * 60));
        $weeksDiff = floor($daysDiff / 7);
        $customWeekNumber = $weeksDiff + 1; // Week 1 starts at base date
        
        return "2025-W" . str_pad($customWeekNumber, 2, '0', STR_PAD_LEFT);
    }

    public function test_admin_dashboard_loads_successfully()
    {
        $response = $this->get('/admin-xyz123');
        
        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
        $response->assertSee('Recent Week');
    }

    public function test_admin_dashboard_defaults_to_current_week()
    {
        $response = $this->get('/admin-xyz123');
        
        $currentWeek = $this->getCurrentCustomWeek();
        
        // Should show feedback from current week
        $response->assertSee('+60123456789');
        $response->assertSee('+60987654321');
        
        // The selected week should be the current week
        $response->assertSee('value="' . $currentWeek . '" selected');
    }

    public function test_admin_dashboard_does_not_show_all_weeks_option()
    {
        $response = $this->get('/admin-xyz123');
        
        // Should NOT contain "All Weeks" option
        $response->assertDontSee('All Weeks');
        $response->assertDontSee('value="all"');
    }

    public function test_admin_dashboard_filters_by_specific_week()
    {
        $currentWeek = $this->getCurrentCustomWeek();
        
        // Test with current week filter
        $response = $this->get('/admin-xyz123?week=' . $currentWeek);
        
        $response->assertStatus(200);
        $response->assertSee('+60123456789');
        $response->assertSee('+60987654321');
    }

    public function test_admin_dashboard_shows_correct_statistics()
    {
        $response = $this->get('/admin-xyz123');
        
        // Should show statistics for the current week
        $response->assertSee('Total Feedbacks');
        $response->assertSee('Selected Week'); // Updated from "This Week"
        $response->assertSee('With Referrer');
        $response->assertSee('Unique Phones');
    }

    public function test_admin_filter_route_works()
    {
        $currentWeek = $this->getCurrentCustomWeek();
        
        $response = $this->get(route('admin.filter', ['week' => $currentWeek]));
        
        $response->assertStatus(200);
        $response->assertSee('+60123456789');
    }
}

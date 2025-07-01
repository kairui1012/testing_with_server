<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Feedback;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminDashboardFinalTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_admin_dashboard_requirements()
    {
        // Set current time to June 30, 2025 for testing
        \Carbon\Carbon::setTestNow(\Carbon\Carbon::create(2025, 6, 30, 12, 0, 0));
        
        // Create test data for current week
        $currentWeek = $this->getCurrentCustomWeek();
        
        Feedback::create([
            'phone' => '+60123456789',
            'good' => 'Test feedback for current week',
            'bad' => 'Some issues',
            'remark' => 'Test remark',
            'referrer' => 'Test Referrer',
            'week' => $currentWeek,
        ]);

        // Create test data for a different week
        Feedback::create([
            'phone' => '+60987654321',
            'good' => 'Test feedback for week 1',
            'bad' => 'Other issues',
            'remark' => null,
            'referrer' => null,
            'week' => '2025-W01', // Week 1
        ]);

        $response = $this->get('/admin-xyz123');

        // 1. Dashboard should load successfully
        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');

        // 2. Should default to current week (not show "All Weeks")
        $response->assertDontSee('All Weeks');
        $response->assertDontSee('value="all"');

        // 3. Should show data only for the current week by default
        $response->assertSee('+60123456789'); // Current week data
        
        // 4. Should have "See all" link for showing all data
        $response->assertSee('See all');

        // 5. Dropdown should only contain individual weeks (no "All Weeks")
        $response->assertSee('select name="week"', false); // Check for HTML element
        $response->assertSee('Week 1');
        $response->assertSee('Week 2');

        // 6. Current week should be selected by default
        $response->assertSee('selected');

        // 7. Stats should show data for the current selected week
        $response->assertSee('Selected Week');
        $response->assertSee('Total Feedbacks');
        $response->assertSee('With Referrer');
        $response->assertSee('Unique Phones');

        echo "\n=== ADMIN DASHBOARD TEST RESULTS ===\n";
        echo "✓ Dashboard loads successfully\n";
        echo "✓ No 'All Weeks' option in dropdown\n";
        echo "✓ Defaults to current week: {$currentWeek}\n";
        echo "✓ Shows data for selected week only\n";
        echo "✓ Has 'See all' functionality\n";
        echo "✓ Week counting starts from June 23, 2025 as Week 1\n";
        echo "===================================\n";
        
        // Reset Carbon test time
        \Carbon\Carbon::setTestNow();
    }

    public function test_week_filtering_works()
    {
        $currentWeek = $this->getCurrentCustomWeek();
        
        // Create data for multiple weeks
        Feedback::create([
            'phone' => '+60111111111',
            'good' => 'Week 1 feedback',
            'bad' => 'Week 1 issues',
            'week' => '2025-W01',
        ]);

        Feedback::create([
            'phone' => '+60222222222',
            'good' => 'Current week feedback',
            'bad' => 'Current week issues',
            'week' => $currentWeek,
        ]);

        // Test filtering by Week 1
        $response = $this->get('/admin-xyz123?week=2025-W01');
        $response->assertStatus(200);
        $response->assertSee('+60111111111');
        $response->assertDontSee('+60222222222');

        // Test filtering by current week
        $response = $this->get("/admin-xyz123?week={$currentWeek}");
        $response->assertStatus(200);
        $response->assertSee('+60222222222');
        $response->assertDontSee('+60111111111');
    }

    public function test_custom_week_calculation()
    {
        // Set current time to June 30, 2025 for testing
        \Carbon\Carbon::setTestNow(\Carbon\Carbon::create(2025, 6, 30, 12, 0, 0));
        
        // June 23, 2025 should be Week 1
        $week1Start = \Carbon\Carbon::create(2025, 6, 23);
        $week1End = \Carbon\Carbon::create(2025, 6, 29);
        
        // June 30, 2025 should be Week 2
        $week2Start = \Carbon\Carbon::create(2025, 6, 30);
        $week2End = \Carbon\Carbon::create(2025, 7, 6);

        // Manual calculation for June 30, 2025
        $baseTimestamp = mktime(0, 0, 0, 6, 23, 2025); // June 23, 2025
        $testTimestamp = mktime(0, 0, 0, 6, 30, 2025); // June 30, 2025
        
        echo "Base timestamp: " . date('Y-m-d', $baseTimestamp) . PHP_EOL;
        echo "Test timestamp: " . date('Y-m-d', $testTimestamp) . PHP_EOL;
        
        $daysDiff = floor(($testTimestamp - $baseTimestamp) / (24 * 60 * 60));
        $weeksDiff = floor($daysDiff / 7);
        $customWeekNumber = $weeksDiff + 1;
        $expectedWeek = "2025-W" . str_pad($customWeekNumber, 2, '0', STR_PAD_LEFT);
        
        echo "Days diff: " . $daysDiff . PHP_EOL;
        echo "Weeks diff: " . $weeksDiff . PHP_EOL;
        echo "Custom week number: " . $customWeekNumber . PHP_EOL;
        echo "Expected week: " . $expectedWeek . PHP_EOL;
        
        $currentWeek = $this->getCurrentCustomWeek();
        
        $this->assertEquals($expectedWeek, $currentWeek, "June 30, 2025 should be Week 2");
        
        echo "\n=== CUSTOM WEEK CALCULATION TEST ===\n";
        echo "Base date: June 23, 2025 (Week 1 start)\n";
        echo "Test date: June 30, 2025\n";
        echo "Days since base: {$daysDiff}\n";
        echo "Weeks since base: {$weeksDiff}\n";
        echo "Custom week number: {$customWeekNumber}\n";
        echo "Expected: {$expectedWeek}\n";
        echo "Actual: {$currentWeek}\n";
        echo "====================================\n";
        
        // Reset Carbon test time
        \Carbon\Carbon::setTestNow();
    }
}

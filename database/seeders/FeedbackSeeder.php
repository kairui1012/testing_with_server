<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feedback;
use Carbon\Carbon;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Helper function to calculate custom week format
        $getCustomWeek = function($date) {
            $baseTimestamp = mktime(0, 0, 0, 6, 23, 2025); // June 23, 2025 is Week 1
            $dateTimestamp = $date->getTimestamp();
            
            // If date is before the base date, default to Week 1
            if ($dateTimestamp < $baseTimestamp) {
                return "2025-W01";
            }
            
            $daysDiff = floor(($dateTimestamp - $baseTimestamp) / (24 * 60 * 60));
            $weeksDiff = floor($daysDiff / 7);
            $customWeekNumber = $weeksDiff + 1;
            return "2025-W" . str_pad($customWeekNumber, 2, '0', STR_PAD_LEFT);
        };

        // Array of realistic phone numbers
        $phoneNumbers = [
            '+60123456789', '+60198765432', '+60187654321', '+60176543210', '+60165432109',
            '+60154321098', '+60143210987', '+60132109876', '+60121098765', '+60110987654',
            '+60109876543', '+60145678901', '+60156789012', '+60167890123', '+60178901234',
            '+60189012345', '+60190123456', '+60112234567', '+60123345678', '+60134456789',
        ];

        // Array of realistic feedback data
        $goodFeedbacks = [
            'Excellent customer service, very professional and helpful staff',
            'Great product quality and fast delivery service',
            'User-friendly interface and easy to navigate',
            'Outstanding support team, resolved my issues quickly',
            'High-quality products at reasonable prices',
            'Very responsive customer service team',
            'Clean facilities and well-organized workspace',
            'Innovative features and modern design approach',
            'Reliable service with consistent quality',
            'Friendly staff and welcoming environment',
            'Efficient process and quick turnaround time',
            'Great value for money and excellent service',
            'Professional presentation and clear communication',
            'Impressive product range and variety',
            'Smooth ordering process and timely delivery',
            'Knowledgeable team with excellent expertise',
            'Modern facilities with latest technology',
            'Competitive pricing and flexible payment options',
            'Excellent after-sales support and service',
            'High standard of work and attention to detail',
        ];

        $badFeedbacks = [
            'Waiting time could be improved, especially during peak hours',
            'Some features are difficult to find in the interface',
            'Pricing could be more competitive compared to competitors',
            'Limited customization options available',
            'Mobile app needs improvement, crashes occasionally',
            'Customer service response time could be faster',
            'Parking facilities are limited and inconvenient',
            'Website navigation could be more intuitive',
            'Some products are frequently out of stock',
            'Email notifications are sometimes delayed',
            'Payment process could be simplified',
            'Documentation needs to be more comprehensive',
            'Limited operating hours on weekends',
            'Some staff members need better product knowledge',
            'Delivery tracking system needs improvement',
            'Return policy could be more flexible',
            'Technical support could be more responsive',
            'User manual is not very clear or detailed',
            'Some services are overpriced for the quality',
            'Appointment scheduling system needs enhancement',
        ];

        $remarks = [
            'Overall very satisfied with the experience',
            'Would definitely recommend to friends and family',
            'Looking forward to using the service again',
            'Good experience, minor improvements needed',
            'Exceeded my expectations in most areas',
            'Professional service, will continue using',
            'Satisfied customer, keep up the good work',
            'Great potential, hope to see more improvements',
            'Reliable service provider, trustworthy',
            'Good value proposition, reasonable pricing',
            null, // Some feedback without remarks
        ];

        $referrers = [
            'Social Media', 'Google Search', 'Friend Referral', 'Facebook Ad',
            'Instagram', 'WhatsApp Group', 'Email Marketing', 'Website',
            'LinkedIn', 'YouTube', 'Business Partner', 'Trade Show',
            'Newspaper Ad', 'Radio Commercial', 'Direct Mail',
            null, // Some feedback without referrer
        ];

        // Generate feedback data for the past 8 weeks (including current week)
        $feedbacks = [];
        
        // Week 1 (June 23-29, 2025) - 8 feedbacks
        for ($i = 0; $i < 8; $i++) {
            $date = Carbon::create(2025, 6, 23)->addDays(rand(0, 6));
            $feedbacks[] = [
                'phone' => $phoneNumbers[array_rand($phoneNumbers)],
                'good' => $goodFeedbacks[array_rand($goodFeedbacks)],
                'bad' => $badFeedbacks[array_rand($badFeedbacks)],
                'remark' => $remarks[array_rand($remarks)],
                'referrer' => $referrers[array_rand($referrers)],
                'week' => $getCustomWeek($date),
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        // Week 2 (June 30-July 6, 2025) - Current week - 12 feedbacks
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::create(2025, 6, 30)->addDays(rand(0, 6));
            $feedbacks[] = [
                'phone' => $phoneNumbers[array_rand($phoneNumbers)],
                'good' => $goodFeedbacks[array_rand($goodFeedbacks)],
                'bad' => $badFeedbacks[array_rand($badFeedbacks)],
                'remark' => $remarks[array_rand($remarks)],
                'referrer' => $referrers[array_rand($referrers)],
                'week' => $getCustomWeek($date),
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        // Week 3 (July 7-13, 2025) - 10 feedbacks
        for ($i = 0; $i < 10; $i++) {
            $date = Carbon::create(2025, 7, 7)->addDays(rand(0, 6));
            $feedbacks[] = [
                'phone' => $phoneNumbers[array_rand($phoneNumbers)],
                'good' => $goodFeedbacks[array_rand($goodFeedbacks)],
                'bad' => $badFeedbacks[array_rand($badFeedbacks)],
                'remark' => $remarks[array_rand($remarks)],
                'referrer' => $referrers[array_rand($referrers)],
                'week' => $getCustomWeek($date),
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        // Week 4 (July 14-20, 2025) - 15 feedbacks
        for ($i = 0; $i < 15; $i++) {
            $date = Carbon::create(2025, 7, 14)->addDays(rand(0, 6));
            $feedbacks[] = [
                'phone' => $phoneNumbers[array_rand($phoneNumbers)],
                'good' => $goodFeedbacks[array_rand($goodFeedbacks)],
                'bad' => $badFeedbacks[array_rand($badFeedbacks)],
                'remark' => $remarks[array_rand($remarks)],
                'referrer' => $referrers[array_rand($referrers)],
                'week' => $getCustomWeek($date),
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        // Week 5 (July 21-27, 2025) - 9 feedbacks
        for ($i = 0; $i < 9; $i++) {
            $date = Carbon::create(2025, 7, 21)->addDays(rand(0, 6));
            $feedbacks[] = [
                'phone' => $phoneNumbers[array_rand($phoneNumbers)],
                'good' => $goodFeedbacks[array_rand($goodFeedbacks)],
                'bad' => $badFeedbacks[array_rand($badFeedbacks)],
                'remark' => $remarks[array_rand($remarks)],
                'referrer' => $referrers[array_rand($referrers)],
                'week' => $getCustomWeek($date),
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        // Week 6 (July 28-August 3, 2025) - 13 feedbacks
        for ($i = 0; $i < 13; $i++) {
            $date = Carbon::create(2025, 7, 28)->addDays(rand(0, 6));
            $feedbacks[] = [
                'phone' => $phoneNumbers[array_rand($phoneNumbers)],
                'good' => $goodFeedbacks[array_rand($goodFeedbacks)],
                'bad' => $badFeedbacks[array_rand($badFeedbacks)],
                'remark' => $remarks[array_rand($remarks)],
                'referrer' => $referrers[array_rand($referrers)],
                'week' => $getCustomWeek($date),
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        // Week 7 (August 4-10, 2025) - 11 feedbacks
        for ($i = 0; $i < 11; $i++) {
            $date = Carbon::create(2025, 8, 4)->addDays(rand(0, 6));
            $feedbacks[] = [
                'phone' => $phoneNumbers[array_rand($phoneNumbers)],
                'good' => $goodFeedbacks[array_rand($goodFeedbacks)],
                'bad' => $badFeedbacks[array_rand($badFeedbacks)],
                'remark' => $remarks[array_rand($remarks)],
                'referrer' => $referrers[array_rand($referrers)],
                'week' => $getCustomWeek($date),
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        // Week 8 (August 11-17, 2025) - 14 feedbacks
        for ($i = 0; $i < 14; $i++) {
            $date = Carbon::create(2025, 8, 11)->addDays(rand(0, 6));
            $feedbacks[] = [
                'phone' => $phoneNumbers[array_rand($phoneNumbers)],
                'good' => $goodFeedbacks[array_rand($goodFeedbacks)],
                'bad' => $badFeedbacks[array_rand($badFeedbacks)],
                'remark' => $remarks[array_rand($remarks)],
                'referrer' => $referrers[array_rand($referrers)],
                'week' => $getCustomWeek($date),
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        // Create all feedback entries
        foreach ($feedbacks as $feedback) {
            Feedback::create($feedback);
        }

        $this->command->info('Created ' . count($feedbacks) . ' fake feedback entries across 8 weeks');
        $this->command->info('Week 1 (Jun 23-29): 8 feedbacks');
        $this->command->info('Week 2 (Jun 30-Jul 6): 12 feedbacks - Current Week');
        $this->command->info('Week 3 (Jul 7-13): 10 feedbacks');
        $this->command->info('Week 4 (Jul 14-20): 15 feedbacks');
        $this->command->info('Week 5 (Jul 21-27): 9 feedbacks');
        $this->command->info('Week 6 (Jul 28-Aug 3): 13 feedbacks');
        $this->command->info('Week 7 (Aug 4-10): 11 feedbacks');
        $this->command->info('Week 8 (Aug 11-17): 14 feedbacks');
    }
}

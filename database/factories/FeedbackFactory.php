<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $goodFeedbacks = [
            'Excellent customer service and professional staff',
            'Great product quality and fast delivery',
            'User-friendly interface and easy navigation',
            'Outstanding support team and quick resolution',
            'High-quality products at reasonable prices',
            'Very responsive and helpful customer service',
            'Clean facilities and well-organized workspace',
            'Innovative features and modern design',
            'Reliable service with consistent quality',
            'Friendly staff and welcoming environment',
        ];

        $badFeedbacks = [
            'Waiting time could be improved during peak hours',
            'Some features are difficult to find',
            'Pricing could be more competitive',
            'Limited customization options available',
            'Mobile app needs improvement',
            'Customer service response time could be faster',
            'Parking facilities are limited',
            'Website navigation could be more intuitive',
            'Some products are frequently out of stock',
            'Email notifications are sometimes delayed',
        ];

        $referrers = [
            'Social Media', 'Google Search', 'Friend Referral', 'Facebook Ad',
            'Instagram', 'WhatsApp Group', 'Email Marketing', 'Website',
            'LinkedIn', 'YouTube', 'Business Partner', 'Trade Show',
            null, // 50% chance of no referrer
        ];

        $remarks = [
            'Overall very satisfied with the experience',
            'Would definitely recommend to friends',
            'Looking forward to using the service again',
            'Good experience, minor improvements needed',
            'Exceeded my expectations in most areas',
            'Professional service, will continue using',
            null, // 40% chance of no remark
        ];

        // Generate Malaysian phone number
        $phoneNumber = '+601' . $this->faker->numberBetween(10000000, 99999999);

        return [
            'phone' => $phoneNumber,
            'good' => $this->faker->randomElement($goodFeedbacks),
            'bad' => $this->faker->randomElement($badFeedbacks),
            'remark' => $this->faker->randomElement($remarks),
            'referrer' => $this->faker->randomElement($referrers),
            'week' => '2025-W02', // Default to current week, can be overridden
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Set a specific week for the feedback
     */
    public function forWeek(string $week): static
    {
        return $this->state(function (array $attributes) use ($week) {
            // Calculate date range for the week
            $weekNumber = (int) substr($week, -2);
            $baseDate = Carbon::create(2025, 6, 23); // Week 1 starts June 23, 2025
            $weekStart = $baseDate->copy()->addWeeks($weekNumber - 1);
            $weekEnd = $weekStart->copy()->addDays(6);
            
            // Random date within the week
            $randomDate = $weekStart->copy()->addDays(rand(0, 6));
            
            return [
                'week' => $week,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ];
        });
    }

    /**
     * Create feedback with referrer
     */
    public function withReferrer(): static
    {
        return $this->state(function (array $attributes) {
            $referrers = [
                'Social Media', 'Google Search', 'Friend Referral', 'Facebook Ad',
                'Instagram', 'WhatsApp Group', 'Email Marketing', 'Website',
                'LinkedIn', 'YouTube', 'Business Partner', 'Trade Show',
            ];
            
            return [
                'referrer' => $this->faker->randomElement($referrers),
            ];
        });
    }

    /**
     * Create feedback without referrer
     */
    public function withoutReferrer(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'referrer' => null,
            ];
        });
    }
}

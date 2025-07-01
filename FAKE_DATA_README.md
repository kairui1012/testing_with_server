# Fake Data Documentation

## Overview
This project includes comprehensive fake data generation capabilities for weekly feedback reports, starting from June 23, 2025 (Week 1).

## Current Status
**Database is clean - No fake data present**
All feedback tables have been reset and are ready for manual data entry.

## Previous Generated Data Summary (for reference)

### Total Records: 276 feedback entries across 8 weeks (when seeded)

**Week Distribution (when populated):**
- **Week 1** (Jun 23-29, 2025): 24 feedbacks
- **Week 2** (Jun 30-Jul 6, 2025): 36 feedbacks ← **Current Week**
- **Week 3** (Jul 7-13, 2025): 30 feedbacks
- **Week 4** (Jul 14-20, 2025): 45 feedbacks
- **Week 5** (Jul 21-27, 2025): 27 feedbacks
- **Week 6** (Jul 28-Aug 3, 2025): 39 feedbacks
- **Week 7** (Aug 4-10, 2025): 33 feedbacks
- **Week 8** (Aug 11-17, 2025): 42 feedbacks

## Data Structure

Each feedback entry includes:
- **Phone**: Malaysian phone numbers (+601xxxxxxxx)
- **Good**: Positive feedback comments
- **Bad**: Constructive criticism and improvement suggestions
- **Remark**: Overall experience comments (nullable)
- **Referrer**: Source of referral (nullable)
- **Week**: Custom week format (2025-W01, 2025-W02, etc.)
- **Timestamps**: Created/updated dates within the respective week

## Referrer Sources
- Social Media
- Google Search
- Friend Referral
- Facebook Ad
- Instagram
- WhatsApp Group
- Email Marketing
- Website
- LinkedIn
- YouTube
- Business Partner
- Trade Show
- No referrer (null values)

## Commands

### Clear All Data (Current Status)
```bash
php artisan migrate:fresh
```

### Regenerate Fake Data (Optional)
```bash
php artisan migrate:fresh --seed
```

### View Data Statistics
```bash
php artisan feedback:stats
```

### Using Factory (Alternative)
```php
// Create 10 feedbacks for Week 2
Feedback::factory()
    ->count(10)
    ->forWeek('2025-W02')
    ->create();

// Create 5 feedbacks with referrer
Feedback::factory()
    ->count(5)
    ->withReferrer()
    ->create();

// Create 3 feedbacks without referrer
Feedback::factory()
    ->count(3)
    ->withoutReferrer()
    ->create();
```

## Admin Dashboard

Access the admin dashboard at: `/admin-xyz123`

Features:
- Defaults to current week (2025-W02)
- Week filter dropdown (no "All Weeks" option)
- Statistics cards showing selected week data
- "See all" link for viewing all weeks
- Pagination support

## Current Week (2025-W02) Statistics
- **Total Feedbacks**: 0 (clean database)
- **With Referrer**: 0
- **Unique Phone Numbers**: 0

## Manual Data Entry
The database is now ready for manual data entry. You can:
1. Use the feedback submission form (login required)
2. Access the admin dashboard at `/admin-xyz123` to view entered data
3. Data will automatically be assigned to the correct week based on submission date

## Notes
- Week calculation starts from June 23, 2025 as Week 1
- Current week is automatically detected (2025-W02 for June 30, 2025)
- Database is clean and ready for real data entry
- All fake data has been removed

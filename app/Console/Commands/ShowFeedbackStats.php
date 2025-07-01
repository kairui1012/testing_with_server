<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Feedback;

class ShowFeedbackStats extends Command
{
    protected $signature = 'feedback:stats';
    protected $description = 'Show feedback statistics';

    public function handle()
    {
        $this->info('=== WEEKLY FEEDBACK REPORT ===');
        $this->info('Total Feedbacks: ' . Feedback::count());
        $this->newLine();

        $this->info('Week Distribution:');
        $weeks = ['2025-W01', '2025-W02', '2025-W03', '2025-W04', '2025-W05', '2025-W06', '2025-W07', '2025-W08'];
        foreach ($weeks as $week) {
            $count = Feedback::where('week', $week)->count();
            $this->line($week . ': ' . $count . ' feedbacks');
        }

        $this->newLine();
        $this->info('Current Week (2025-W02) Stats:');
        $currentWeek = Feedback::where('week', '2025-W02')->count();
        $withReferrer = Feedback::where('week', '2025-W02')->whereNotNull('referrer')->count();
        $uniquePhones = Feedback::where('week', '2025-W02')->distinct('phone')->count();
        
        $this->line('Total: ' . $currentWeek);
        $this->line('With Referrer: ' . $withReferrer);
        $this->line('Unique Phones: ' . $uniquePhones);

        return 0;
    }
}

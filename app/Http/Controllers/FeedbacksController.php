<?php

namespace App\Http\Controllers;

use App\Models\Feedbacks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FeedbacksController extends Controller
{
    protected function getOrCreateWeeklyFeedback($userPhone)
    {
        $week = $this->getCurrentCustomWeek();
        return Feedback::firstOrCreate(
            ['phone' => $userPhone, 'week' => $week],
            ['good' => null, 'bad' => null, 'remark' => null , 'referrer' => null]
        );
    }

    // 合并后的提交方法
    public function submitFeedback(Request $request)
    {
        $request->validate([
            'good' => 'required|string',
            'bad' => 'required|string',
            'remark' => 'nullable|string',
            'referrer' => 'nullable|string',
        ]);

        $userPhone = Auth::user()->phone;
        $feedback = $this->getOrCreateWeeklyFeedback($userPhone);

        $feedback->good = $request->input('good');
        $feedback->bad = $request->input('bad');
        $feedback->remark = $request->input('remark');
        $feedback->referrer = $request->input('referrer');
        $feedback->save();

        return redirect()->back()->with('success', '反馈已保存');
    }

    // Admin Dashboard Methods
    public function adminDashboard(Request $request)
    {
        try {
            // Default to current week - no 'all' option allowed
            $currentWeek = $this->getCurrentCustomWeek();
            $selectedWeek = $request->get('week', $currentWeek);
            
            // Always filter by a specific week - no 'all' option
            $query = Feedback::query()
                ->where('week', $selectedWeek)
                ->orderBy('created_at', 'desc');
            
            $feedbacks = $query->paginate(10);
            
            // Get all available weeks for filter dropdown
            $availableWeeks = $this->getAvailableWeeks();
            
            return view('admin.dashboard-simple', compact('feedbacks', 'availableWeeks', 'selectedWeek'));
        } catch (\Exception $e) {
            // If there's any error, return with current week data
            $currentWeek = $this->getCurrentCustomWeek();
            $feedbacks = collect()->paginate(10);
            $availableWeeks = collect();
            $selectedWeek = $currentWeek;
            
            return view('admin.dashboard-simple', compact('feedbacks', 'availableWeeks', 'selectedWeek'));
        }
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

    private function getAvailableWeeks()
    {
        // Get all weeks from feedback data and convert to custom week format
        $feedbackWeeks = Feedback::select('week')
            ->distinct()
            ->whereNotNull('week')
            ->orderBy('week', 'desc')
            ->pluck('week');

        $availableWeeks = collect();

        // Generate custom weeks from June 23, 2025 to current date
        $baseDate = Carbon::create(2025, 6, 23); // June 23, 2025 - Week 1
        $currentDate = Carbon::now();
        
        $weekNumber = 1;
        $weekStart = $baseDate->copy();
        
        while ($weekStart->lte($currentDate)) {
            $weekEnd = $weekStart->copy()->addDays(6);
            $weekKey = "2025-W" . str_pad($weekNumber, 2, '0', STR_PAD_LEFT);
            
            $availableWeeks->push([
                'value' => $weekKey,
                'label' => "Week {$weekNumber} ({$weekStart->format('M d')} - {$weekEnd->format('M d, Y')})"
            ]);
            
            $weekStart->addWeek();
            $weekNumber++;
        }
        
        return $availableWeeks->reverse()->values(); // Most recent weeks first
    }

    public function filterFeedbacks(Request $request)
    {
        return $this->adminDashboard($request);
    }
}

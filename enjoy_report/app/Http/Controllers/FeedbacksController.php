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
        // Calculate week based on our custom system starting June 23, 2025 as Week 1
        $startDate = Carbon::parse('2025-06-23'); // Week 1 start date
        $currentDate = Carbon::now();
        
        $weekNumber = 1;
        $weekStart = $startDate->copy();
        
        // Find which week we're currently in
        while ($weekStart->lte($currentDate)) {
            $weekEnd = $weekStart->copy()->addDays(6);
            if ($currentDate->between($weekStart, $weekEnd)) {
                $week = "2025-W" . sprintf('%02d', $weekNumber);
                break;
            }
            $weekStart->addWeek();
            $weekNumber++;
        }
        
        // Fallback to default week format if calculation fails
        if (!isset($week)) {
            $week = now()->format('o-\WW');
        }
        
        return Feedbacks::firstOrCreate(
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
}

<?php

namespace App\Http\Controllers;

use App\Models\Feedbacks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FeedbacksController extends Controller
{
    protected function getCurrentWeek()
    {
        // Week 1 starts on June 23, 2025 - same as admin dashboard
        $startDate = Carbon::parse('2025-06-23');
        $currentDate = Carbon::now();
        
        // Calculate which week we're in
        $weekNumber = 1;
        $weekStart = $startDate->copy();
        
        while ($weekStart->lte($currentDate)) {
            $weekEnd = $weekStart->copy()->addDays(6);
            if ($currentDate->between($weekStart, $weekEnd)) {
                return sprintf('2025-W%02d', $weekNumber);
            }
            $weekStart->addWeek();
            $weekNumber++;
        }
        
        // Default to Week 1 if calculation fails
        return '2025-W01';
    }

    protected function getOrCreateWeeklyFeedback($userPhone)
    {
        $week = $this->getCurrentWeek();
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

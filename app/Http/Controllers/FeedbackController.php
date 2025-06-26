<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    // 自动获取或创建当前用户当前周的 feedback 记录
    protected function getOrCreateWeeklyFeedback($userPhone)
    {
        $week = now()->format('o-\WW');

        return Feedback::firstOrCreate(
            ['phone' => $userPhone, 'week' => $week],
            ['good' => null, 'bad' => null, 'remark' => null]
        );
    }

    // 保存 good（好评）
    public function saveGood(Request $request)
    {
        $request->validate([
            'good' => 'required|string',
        ]);

        $userPhone = Auth::user()->phone;
        $feedback = $this->getOrCreateWeeklyFeedback($userPhone);

        $feedback->good = $request->input('good');
        $feedback->save();

        return redirect()->back()->with('success', '好评已保存');
    }

    // 保存 bad（差评）
    public function saveBad(Request $request)
    {
        $request->validate([
            'bad' => 'required|string',
        ]);

        $userPhone = Auth::user()->phone;
        $feedback = $this->getOrCreateWeeklyFeedback($userPhone);

        $feedback->bad = $request->input('bad');
        $feedback->save();

        return redirect()->back()->with('success', '差评已保存');
    }

    // 保存完整的周报
    public function submitWeeklyReport(Request $request)
    {
        $request->validate([
            'good_feedback' => 'nullable|string',
            'bad_feedback' => 'nullable|string',
            'referrer' => 'nullable|string',
            'remark' => 'nullable|string',
        ]);

        // Custom validation to ensure at least one feedback field is provided
        if (empty($request->input('good_feedback')) && empty($request->input('bad_feedback'))) {
            return redirect()->back()
                ->withErrors(['feedback' => 'At least one feedback field (Good Feedback or Bad Feedback) must be filled out.'])
                ->withInput();
        }

        $userPhone = Auth::user()->phone ?? Auth::user()->email; // Fallback to email if phone not available
        $feedback = $this->getOrCreateWeeklyFeedback($userPhone);

        // Update all fields
        $feedback->update([
            'good' => $request->input('good_feedback'),
            'bad' => $request->input('bad_feedback'),
            'referrer' => $request->input('referrer'),
            'remark' => $request->input('remark'),
        ]);

        return redirect()->back()->with('success', 'Weekly report submitted successfully!');
    }

    // 保存每日日志
    public function submitDailyLog(Request $request)
    {
        $request->validate([
            'mission_requirements' => 'nullable|array',
            'mission_requirements.*' => 'string',
        ]);

        $userPhone = Auth::user()->phone ?? Auth::user()->email; // Fallback to email if phone not available
        
        // Create daily log entry
        $dailyLog = Feedback::create([
            'phone' => $userPhone,
            'week' => now()->format('Y-m-d'), // Use date for daily logs instead of week
            'form_description' => 'Daily Log',
            'good' => json_encode([
                'mission_requirements' => $request->input('mission_requirements', [])
            ]),
            'referrer' => 'daily_log',
        ]);

        return redirect()->back()->with('success', 'Daily log submitted successfully!');
    }


}

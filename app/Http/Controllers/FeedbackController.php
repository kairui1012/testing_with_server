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


}

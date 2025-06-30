<?php

namespace App\Http\Controllers;

use App\Models\Feedbacks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbacksController extends Controller
{
    protected function getOrCreateWeeklyFeedback($userPhone)
    {
        $week = now()->format('o-\WW');
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

<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbacksController extends Controller
{
    protected function getOrCreateWeeklyFeedback($userPhone)
    {
        $week = now()->format('o-\WW');
        return Feedback::firstOrCreate(
            ['phone' => $userPhone, 'week' => $week],
            ['good' => null, 'bad' => null, 'remark' => null , 'remark' => null]
        );
    }

    // 合并后的提交方法
    public function submitFeedback(Request $request)
    {
        $request->validate([
            'good' => 'required|string',
            'bad' => 'required|string',
            'remark' => 'nullable|string',
            'reference' => 'nullable|string',
        ]);

        $userPhone = Auth::user()->phone;
        $feedback = $this->getOrCreateWeeklyFeedback($userPhone);

        $feedback->good = $request->input('good');
        $feedback->bad = $request->input('bad');
        $feedback->remark = $request->input('remark');
        $feedback->reference = $request->input('reference');
        $feedback->save();

        return redirect()->back()->with('success', '反馈已保存');
    }
}

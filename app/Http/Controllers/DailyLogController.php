<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DailyLogController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Get or create today's daily log for the current user
        $dailyLog = DailyLog::firstOrCreate(
            [
                'user_id' => $user->id,
                'log_date' => $today,
            ],
            [
                'open_enjoy_app' => false,
                'check_in' => false,
                'play_view_video' => false,
            ]
        );

        return view('daily-log', compact('dailyLog'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'field' => 'required|in:open_enjoy_app,check_in,play_view_video',
            'value' => 'required|boolean',
        ]);

        $user = Auth::user();
        $today = Carbon::today();

        $dailyLog = DailyLog::firstOrCreate(
            [
                'user_id' => $user->id,
                'log_date' => $today,
            ],
            [
                'open_enjoy_app' => false,
                'check_in' => false,
                'play_view_video' => false,
            ]
        );

        $dailyLog->update([
            $request->field => $request->value,
        ]);

        return response()->json(['success' => true]);
    }
}

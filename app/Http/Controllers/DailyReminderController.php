<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class DailyReminderController extends Controller
{
    // Display the form with the user's current settings
    public function index()
    {
        $user = auth()->user();
        // Fetch reminders and key them by weekday for easy access in the view
        $reminders = $user->dailyReminders()->get()->keyBy('weekday');
        $weekdays = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
        ];

        return view('reminders.daily-reminder', compact('reminders', 'weekdays'));
    }

    // Save the user's settings
    public function store(Request $request)
    {
        // Basic validation
        $request->validate([
            'reminders.*.time' => 'nullable|date_format:H:i',
            'reminders.*.message' => 'nullable|string|max:1000',
            'reminders.*.is_active' => 'nullable|boolean',
        ]);

        $user = auth()->user();
        $inputReminders = $request->input('reminders', []);

        foreach ($inputReminders as $weekday => $data) {
            // Only save if a time is provided for that day
            if (!empty($data['time'])) {
                $user->dailyReminders()->updateOrCreate(
                    ['weekday' => $weekday], // Find by this condition
                    [ // Update or create with this data
                        'time' => $data['time'],
                        'message' => $data['message'],
                        'is_active' => isset($data['is_active']), // Checkbox value
                    ]
                );
            } else {
                // If time is cleared, delete the reminder for that day
                $user->dailyReminders()->where('weekday', $weekday)->delete();
            }
        }

        return redirect()->route('reminder.index')->with('success', 'Reminder settings updated successfully!');
    }
}

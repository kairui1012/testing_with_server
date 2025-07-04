<?php

namespace App\Http\Controllers;

use App\Models\Feedbacks;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // Generate week options starting from June 23, 2025 (Week 1)
        $startDate = Carbon::parse('2025-06-23'); // Week 1 start date
        $currentDate = Carbon::now();
        $endDate = $currentDate->copy()->addWeeks(2); // Show a few weeks ahead
        $weeks = [];

        $weekNumber = 1;
        $weekStart = $startDate->copy();

        while ($weekStart->lte($endDate)) {
            $weekEnd = $weekStart->copy()->addDays(4);
            $weekLabel = "Week {$weekNumber} ({$weekStart->format('M d')} - {$weekEnd->format('M d, Y')})";
            $weekValue = $weekStart->format('Y-m-d');

            $weeks[] = [
                'label' => $weekLabel,
                'value' => $weekValue,
                'week_number' => $weekNumber
            ];

            $weekStart->addWeek();
            $weekNumber++;
        }

        // Get selected week - default to current week, but allow 'all' for see all functionality
        $selectedWeek = $request->get('week');

        // If no week is selected, default to current week
        if (!$selectedWeek) {
            $currentDate = Carbon::now();
            $currentWeekStart = $startDate->copy();
            $weekNumber = 1;

            // Find which week we're currently in
            while ($currentWeekStart->lte($currentDate)) {
                $currentWeekEnd = $currentWeekStart->copy()->addDays(6);
                if ($currentDate->between($currentWeekStart, $currentWeekEnd)) {
                    $selectedWeek = $currentWeekStart->format('Y-m-d');
                    break;
                }
                $currentWeekStart->addWeek();
                $weekNumber++;
            }

            // If we couldn't find current week, default to the most recent week
            if (!$selectedWeek && !empty($weeks)) {
                $selectedWeek = end($weeks)['value'];
            }
        }

        // Build query - filter by selected week unless it's 'all'
        $query = Feedbacks::query();

        if ($selectedWeek && $selectedWeek !== 'all') {
            $weekStart = Carbon::parse($selectedWeek);
            $weekEnd = $weekStart->copy()->addDays(6);
            $query->whereBetween('created_at', [$weekStart->startOfDay(), $weekEnd->endOfDay()]);
        }

        // Get feedbacks with pagination
        $feedbacks = $query->orderBy('created_at', 'desc')->paginate(10);

        foreach ($feedbacks as $feedback) {
            $date = $feedback->created_at->copy()->startOfWeek(Carbon::MONDAY);
            $start = $date->copy();
            $end = $date->copy()->addDays(4); // Mon-Fri
            $feedback->week_range = $start->format('M d') . ' - ' . $end->format('M d, Y');
        }

        // Calculate statistics
        $totalFeedbacks = Feedbacks::count();
        $thisWeekFeedbacks = Feedbacks::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();

        return view('admin.dashboard', compact('feedbacks', 'weeks', 'selectedWeek', 'totalFeedbacks', 'thisWeekFeedbacks'));
    }

    public function filter(Request $request)
    {
        return $this->dashboard($request);
    }
}

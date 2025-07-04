<?php


namespace App\Livewire;

use App\Models\Feedbacks;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ShowFeedbacks extends Component
{
    public $feedbacks;
    public $editingField = null;
    public $editingValue = '';
    public $editingId = null;
    public $editingColumn = null;
    public $weeks = [];

    public function mount()
    {
        $this->loadFeedbacks();
    }

    public $selectedWeek = '';

    public function getFilteredFeedbacksProperty()
    {
        $query = $this->feedbacks;

        if ($this->selectedWeek) {
            $query = $query->where('week', $this->selectedWeek);
        }

        return $query;
    }

    public function getWeekRange($week)
    {
        if (!$week) return '';
        try {
            $dt = new \DateTime();
            $dt->setISODate(substr($week, 0, 4), substr($week, 6, 2));
            $start = $dt->format('j M Y'); // e.g. 30 Jun 2025
            $dt->modify('+4 days'); // Monday to Friday
            $end = $dt->format('j M Y'); // e.g. 4 Jul 2025
            return "$start - $end";
        } catch (\Exception $e) {
            return $week;
        }
    }

    public function loadFeedbacks()
    {
        $this->feedbacks = Feedbacks::where('phone', Auth::user()->phone)->get();
    }

    public function editField($id, $field)
    {
        $this->editingField = $field . '-' . $id;
        $this->editingId = $id;
        $this->editingColumn = $field;

        // 获取当前值
        $feedback = collect($this->feedbacks)->firstWhere('id', $id);
        $this->editingValue = $feedback->{$field} ?? '';
    }

    public function saveField()
    {
        if ($this->editingId && $this->editingColumn) {
            // Find and verify ownership
            $feedback = Feedbacks::find($this->editingId);
            if ($feedback && $feedback->phone === Auth::user()->phone) {
                $feedback->{$this->editingColumn} = $this->editingValue;
                $feedback->save();

                // Refresh data
                $this->loadFeedbacks();
            }
        }

        $this->cancelEdit();
    }

    public function cancelEdit()
    {
        $this->editingField = null;
        $this->editingValue = '';
        $this->editingId = null;
        $this->editingColumn = null;
    }


    public function render()
    {
        $currentWeekRange = $this->getWeekRange($this->selectedWeek);
        return view('livewire.show-feedbacks', [
            'currentWeekRange' => $currentWeekRange,
        ]);
    }
}

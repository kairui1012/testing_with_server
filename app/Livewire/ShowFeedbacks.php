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

    public function mount()
    {
        $this->loadFeedbacks();
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
        return view('livewire.show-feedbacks');
    }
}

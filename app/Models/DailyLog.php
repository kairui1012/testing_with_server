<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'log_date',
        'open_enjoy_app',
        'check_in',
        'play_view_video',
    ];

    protected $casts = [
        'log_date' => 'date',
        'open_enjoy_app' => 'boolean',
        'check_in' => 'boolean',
        'play_view_video' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';
    
    protected $fillable = ['phone', 'form_description', 'good', 'bad', 'remark', 'referrer', 'week'];
}

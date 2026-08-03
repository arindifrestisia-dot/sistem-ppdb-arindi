<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewSchedule extends Model
{
    protected $fillable = [
        'schedule_key',
        'interview_date',
        'session_label',
        'interview_time',
        'room',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'interview_date' => 'date',
            'is_active' => 'boolean',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbNotificationLog extends Model
{
    protected $fillable = [
        'student_registration_id',
        'user_id',
        'notification_key',
        'channel',
        'recipient',
        'subject',
        'message',
        'deduplication_key',
        'status',
        'error',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }
}

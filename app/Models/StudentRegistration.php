<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentRegistration extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'nickname',
        'gender',
        'birth_place',
        'birth_date',
        'weight_kg',
        'height_cm',
        'home_address',
        'origin_region',
        'citizenship',
        'special_needs',
        'child_order',
        'siblings_total',
        'medical_history',
        'father_name',
        'father_birth_info',
        'father_job',
        'father_education',
        'father_income',
        'father_phone',
        'mother_name',
        'mother_birth_info',
        'mother_job',
        'mother_education',
        'mother_income',
        'mother_phone',
        'father_email',
        'mother_email',
        'registration_number',
        'submitted_at',
        'locked_at',
        'interview_schedule_key',
        'interview_date',
        'interview_day_name',
        'interview_time',
        'interview_room',
        'interview_selected_at',
        'selection_result',
        'selection_published_at',
        'verification_status',
        'verification_notes',
        'verified_by',
        'verified_at',
        'child_photo_path',
        'parents_id_card_path',
        'birth_certificate_path',
        'family_card_path',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'submitted_at' => 'datetime',
            'locked_at' => 'datetime',
            'interview_date' => 'date',
            'interview_selected_at' => 'datetime',
            'selection_published_at' => 'datetime',
            'verified_at' => 'datetime',
            'special_needs' => 'boolean',
            'weight_kg' => 'decimal:2',
            'height_cm' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}

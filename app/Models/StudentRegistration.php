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
        'religion',
        'weight_kg',
        'height_cm',
        'home_address',
        'origin_region',
        'citizenship',
        'special_needs',
        'special_needs_description',
        'child_status',
        'blood_type',
        'child_order',
        'siblings_total',
        'medical_history',
        'father_name',
        'father_birth_info',
        'father_religion',
        'father_citizenship',
        'father_status',
        'father_job',
        'father_education',
        'father_income',
        'father_phone',
        'father_address',
        'mother_name',
        'mother_birth_info',
        'mother_religion',
        'mother_citizenship',
        'mother_status',
        'mother_job',
        'mother_education',
        'mother_income',
        'mother_phone',
        'mother_address',
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
        'interview_completed_at',
        'interview_notes',
        'selection_result',
        'selection_published_at',
        'reregistration_order_id',
        'reregistration_snap_token',
        'reregistration_snap_redirect_url',
        'reregistration_amount',
        'reregistration_status',
        'reregistration_payment_type',
        'reregistration_proof_path',
        'reregistration_paid_at',
        'reregistration_verified_by',
        'reregistration_verified_at',
        'reregistration_midtrans_payload',
        'verification_status',
        'verification_notes',
        'verified_by',
        'verified_at',
        'child_photo_path',
        'parents_id_card_path',
        'birth_certificate_path',
        'family_card_path',
        'custom_form_data',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'submitted_at' => 'datetime',
            'locked_at' => 'datetime',
            'interview_date' => 'date',
            'interview_selected_at' => 'datetime',
            'interview_completed_at' => 'datetime',
            'selection_published_at' => 'datetime',
            'reregistration_paid_at' => 'datetime',
            'reregistration_verified_at' => 'datetime',
            'reregistration_midtrans_payload' => 'array',
            'verified_at' => 'datetime',
            'special_needs' => 'boolean',
            'weight_kg' => 'decimal:2',
            'height_cm' => 'decimal:2',
            'custom_form_data' => 'array',
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

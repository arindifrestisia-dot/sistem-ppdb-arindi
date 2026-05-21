<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PpdbFormPayment extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'snap_token',
        'snap_redirect_url',
        'amount',
        'status',
        'payment_type',
        'paid_at',
        'midtrans_payload',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'paid_at' => 'datetime',
            'midtrans_payload' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPaid(): bool
    {
        return in_array($this->status, ['settlement', 'capture'], true) && $this->paid_at !== null;
    }
}

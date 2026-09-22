<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'method',
        'status',
        'amount',
        'currency',
        'reference',
        'note',
        'proof_media_id',
        'received_at',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'received_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

protected static function booted(): void
{
    static::saving(function (ReservationPayment $payment): void {
        if ($payment->status === 'verified') {
            $payment->verified_at ??= now();
            $payment->verified_by ??= auth()->id();
        }
    });

    static::saved(function (ReservationPayment $payment): void {
        $payment->reservation?->syncPaymentStatus();
    });

    static::deleted(function (ReservationPayment $payment): void {
        $payment->reservation?->syncPaymentStatus();
    });
}

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function proofMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'proof_media_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
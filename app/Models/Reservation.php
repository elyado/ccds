<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'event_schedule_id',
        'event_schedule_ticket_type_id',
        'folio',
        'customer_name',
        'customer_phone',
        'customer_email',
        'quantity',
        'unit_price',
        'total_amount',
        'currency',
        'source',
        'status',
        'payment_status',
        'expires_at',
        'internal_note',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

protected static function booted(): void
{
    static::saving(function (Reservation $reservation): void {
        if (
            ! $reservation->event_schedule_id ||
            $reservation->status !== 'active'
        ) {
            return;
        }

        $schedule = EventSchedule::find($reservation->event_schedule_id);

        if (! $schedule) {
            return;
        }

        $reservedQuantity = static::query()
            ->where('event_schedule_id', $reservation->event_schedule_id)
            ->where('status', 'active')
            ->when(
                $reservation->exists,
                fn ($query) => $query->whereKeyNot($reservation->getKey())
            )
            ->sum('quantity');

        if (($reservedQuantity + (int) $reservation->quantity) > $schedule->public_capacity) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'quantity' => "La función solo tiene {$schedule->public_capacity} lugares públicos; ya hay {$reservedQuantity} reservados.",
            ]);
        }
    });

    static::creating(function (Reservation $reservation): void {
        if (blank($reservation->folio)) {
            $nextNumber = (static::withTrashed()->max('id') ?? 0) + 1;

            $reservation->folio = 'SOL-'.str_pad(
                (string) $nextNumber,
                6,
                '0',
                STR_PAD_LEFT
            );
        }

        $reservation->created_by ??= auth()->id();
        $reservation->updated_by ??= auth()->id();
    });

    static::updating(function (Reservation $reservation): void {
        $reservation->updated_by = auth()->id();
    });
} 

    public function eventSchedule(): BelongsTo
    {
        return $this->belongsTo(EventSchedule::class);
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(
            EventScheduleTicketType::class,
            'event_schedule_ticket_type_id'
        );
    }

    public function payments(): HasMany
    {
        return $this->hasMany(ReservationPayment::class);
    }

    public function checkIns(): HasMany
    {
        return $this->hasMany(ReservationCheckIn::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getCheckedInQuantityAttribute(): int
    {
        return (int) $this->checkIns()->sum('quantity');
    }

    public function getRemainingCheckInQuantityAttribute(): int
    {
        return max(0, $this->quantity - $this->checked_in_quantity);
    }
    public function syncPaymentStatus(): void
{
    if ($this->payment_status === 'complimentary') {
        return;
    }

    $verifiedAmount = $this->payments()
        ->where('status', 'verified')
        ->sum('amount');

    $hasReceivedProof = $this->payments()
        ->whereNotNull('received_at')
        ->exists();

   $paymentStatus = $verifiedAmount > 0
    && $verifiedAmount >= $this->total_amount
        ? 'verified'
        : ($hasReceivedProof ? 'proof_received' : 'pending');

    $this->updateQuietly([
        'payment_status' => $paymentStatus,
    ]);
}

public function getVerifiedAmountAttribute(): float
{
    return (float) $this->payments()
        ->where('status', 'verified')
        ->sum('amount');
}

public function getPendingBalanceAttribute(): float
{
    return max(0, (float) $this->total_amount - $this->verified_amount);
}
}
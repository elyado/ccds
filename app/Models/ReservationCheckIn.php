<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationCheckIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'quantity',
        'checked_in_at',
        'note',
        'checked_in_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'checked_in_at' => 'datetime',
    ];
protected static function booted(): void
{
    static::saving(function (ReservationCheckIn $checkIn): void {
        $reservation = $checkIn->reservation;

        if (! $reservation) {
            return;
        }

      if (! in_array($reservation->payment_status, ['verified', 'complimentary'], true)) {
    $balance = number_format($reservation->pending_balance, 2);

    throw \Illuminate\Validation\ValidationException::withMessages([
        'quantity' => "Pago incompleto. Faltan \${$balance} para permitir el check-in.",
    ]);
}

        $alreadyCheckedIn = static::query()
            ->where('reservation_id', $checkIn->reservation_id)
            ->when(
                $checkIn->exists,
                fn ($query) => $query->whereKeyNot($checkIn->getKey())
            )
            ->sum('quantity');

        if (($alreadyCheckedIn + (int) $checkIn->quantity) > $reservation->quantity) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'quantity' => 'El check-in excede los asistentes incluidos en esta reserva.',
            ]);
        }
    });

    
}
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }
}
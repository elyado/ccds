<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventSchedule extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'venue_id',
        'venue_layout_id',
        'rescheduled_from_id',
        'starts_at',
        'ends_at',
        'doors_at',
        'base_capacity_snapshot',
        'authorized_capacity',
        'production_capacity',
        'complimentary_capacity',
        'public_capacity',
        'capacity_override_authorized',
        'capacity_override_reason',
        'capacity_override_by',
        'capacity_override_at',
        'access_type',
        'price_amount',
        'price_label',
        'currency',
        'status',
        'show_capacity',
        'show_availability',
        'cta_label',
        'cta_url',
        'public_note',
        'internal_note',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'doors_at' => 'datetime',
        'capacity_override_authorized' => 'boolean',
        'capacity_override_at' => 'datetime',
        'price_amount' => 'decimal:2',
        'show_capacity' => 'boolean',
        'show_availability' => 'boolean',
        'base_capacity_snapshot' => 'integer',
        'authorized_capacity' => 'integer',
        'production_capacity' => 'integer',
        'complimentary_capacity' => 'integer',
        'public_capacity' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function venueLayout(): BelongsTo
    {
        return $this->belongsTo(VenueLayout::class);
    }

    public function rescheduledFrom(): BelongsTo
    {
        return $this->belongsTo(self::class, 'rescheduled_from_id');
    }
    public function ticketTypes(): HasMany
    {
        return $this->hasMany(EventScheduleTicketType::class);
    }

    public function rescheduledTo(): HasMany
    {
        return $this->hasMany(self::class, 'rescheduled_from_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function capacityOverrideBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'capacity_override_by');
    }
    public function reservations(): HasMany
{
    return $this->hasMany(Reservation::class);
}
}

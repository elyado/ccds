<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventScheduleTicketType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'event_schedule_id',
        'name',
        'code',
        'description',
        'access_kind',
        'price_amount',
        'currency',
        'capacity_limit',
        'sale_unit',
        'units_per_sale',
        'minimum_per_purchase',
        'maximum_per_purchase',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price_amount' => 'decimal:2',
        'capacity_limit' => 'integer',
        'units_per_sale' => 'integer',
        'minimum_per_purchase' => 'integer',
        'maximum_per_purchase' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(EventSchedule::class, 'event_schedule_id');
    }
}
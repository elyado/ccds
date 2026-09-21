<?php

namespace App\Models;

use App\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'summary',
        'description',
        'area_m2',
        'length_m',
        'width_m',
        'height_m',
        'accessibility',
        'included_equipment',
        'additional_equipment',
        'reference_price',
        'deposit_amount',
        'minimum_hours',
        'restrictions',
        'policies',
        'indicative_hours',
        'video_url',
        'cta_label',
        'cta_url',
        'status',
        'published_at',
        'cover_media_id',
        'floor_plan_media_id',
        'technical_sheet_media_id',
    ];

    protected $casts = [
        'area_m2' => 'decimal:2',
        'length_m' => 'decimal:2',
        'width_m' => 'decimal:2',
        'height_m' => 'decimal:2',
        'reference_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'minimum_hours' => 'integer',
        'indicative_hours' => 'array',
        'published_at' => 'datetime',
        'status' => ContentStatus::class,
    ];

    public function layouts(): HasMany
    {
        return $this->hasMany(VenueLayout::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
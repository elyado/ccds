<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VenueLayout extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'venue_id',
        'name',
        'slug',
        'base_capacity',
        'description',
        'technical_notes',
        'diagram_media_id',
        'status',
    ];

    protected $casts = [
        'base_capacity' => 'integer',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }
}
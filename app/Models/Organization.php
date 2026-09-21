<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'summary',
        'description',
        'website_url',
        'public_links',
        'logo_media_id',
        'status',
    ];

    protected $casts = [
        'public_links' => 'array',
    ];

    public function eventParticipations(): HasMany
    {
        return $this->hasMany(EventParticipant::class);
    }
}
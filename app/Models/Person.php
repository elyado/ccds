<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'artistic_name',
        'slug',
        'bio',
        'email',
        'phone',
        'public_links',
        'photo_media_id',
        'publication_consent_status',
        'publication_consented_at',
        'status',
    ];

    protected $casts = [
        'public_links' => 'array',
        'publication_consented_at' => 'datetime',
    ];

    public function eventParticipations(): HasMany
    {
        return $this->hasMany(EventParticipant::class);
    }
}
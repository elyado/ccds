<?php

namespace App\Models;

use App\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'event_series_id', 'category_id', 'poster_media_id', 'cover_media_id',
        'mobile_media_id', 'seo_image_media_id', 'title', 'slug', 'summary',
        'body', 'discipline', 'event_type', 'modality', 'target_audience',
        'recommended_age', 'language', 'duration_minutes', 'video_url',
        'is_free', 'reference_price_amount', 'reference_price_label',
        'cta_label', 'cta_url', 'access_notes', 'responsible_user_id',
        'technical_requirements', 'internal_notes', 'status',
        'publish_starts_at', 'publish_ends_at', 'sort_order', 'is_featured',
        'show_on_home', 'show_in_archive', 'finished_at', 'cancellation_note',
        'seo_title', 'seo_description',
    ];

    protected $casts = [
        'is_free' => 'boolean', 'reference_price_amount' => 'decimal:2',
        'duration_minutes' => 'integer', 'sort_order' => 'integer',
        'is_featured' => 'boolean', 'show_on_home' => 'boolean',
        'show_in_archive' => 'boolean', 'publish_starts_at' => 'datetime',
        'publish_ends_at' => 'datetime', 'finished_at' => 'datetime',
        'status' => ContentStatus::class,
    ];

    protected static function booted(): void
    {
        static::saving(function (Event $event): void {
            if (! $event->isDirty('title') && filled($event->slug)) {
                return;
            }

            $baseSlug = Str::slug($event->title) ?: 'evento';
            $slug = $baseSlug;
            $suffix = 2;

            while (static::withTrashed()
                ->where('slug', $slug)
                ->when($event->exists, fn ($query) => $query->whereKeyNot($event->getKey()))
                ->exists()) {
                $slug = "{$baseSlug}-{$suffix}";
                $suffix++;
            }

            $event->slug = $slug;
        });
    }

    public function series(): BelongsTo { return $this->belongsTo(EventSeries::class, 'event_series_id'); }
    public function participants(): HasMany { return $this->hasMany(EventParticipant::class); }
    public function category(): BelongsTo { return $this->belongsTo(EventCategory::class); }
    public function schedules(): HasMany { return $this->hasMany(EventSchedule::class); }
    public function posterMedia(): BelongsTo { return $this->belongsTo(Media::class, 'poster_media_id'); }
    public function coverMedia(): BelongsTo { return $this->belongsTo(Media::class, 'cover_media_id'); }
    public function mobileMedia(): BelongsTo { return $this->belongsTo(Media::class, 'mobile_media_id'); }
    public function seoImageMedia(): BelongsTo { return $this->belongsTo(Media::class, 'seo_image_media_id'); }
    public function responsibleUser(): BelongsTo { return $this->belongsTo(User::class, 'responsible_user_id'); }
    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
    public function publishedBy(): BelongsTo { return $this->belongsTo(User::class, 'published_by'); }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'media';

    protected $fillable = [
        'model_type',
        'model_id',
        'uuid',
        'collection_name',
        'name',
        'file_name',
        'disk',
        'conversions_disk',
        'mime_type',
        'extension',
        'size',
        'width',
        'height',
        'duration_seconds',
        'alt_text',
        'credit',
        'rights',
        'visibility',
        'manipulations',
        'custom_properties',
        'generated_conversions',
        'responsive_images',
        'order_column',
        'uploaded_by',
    ];

    protected $casts = [
        'manipulations' => 'array',
        'custom_properties' => 'array',
        'generated_conversions' => 'array',
        'responsive_images' => 'array',
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'duration_seconds' => 'integer',
        'order_column' => 'integer',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->file_name);
    }
}

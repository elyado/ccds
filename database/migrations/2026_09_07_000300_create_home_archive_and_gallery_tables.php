<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('home_slides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title_override', 220)->nullable();
            $table->string('subtitle_override', 255)->nullable();
            $table->text('description_override')->nullable();
            $table->foreignId('background_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('poster_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('mobile_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('background_alt_text', 255)->nullable();
            $table->string('poster_alt_text', 255)->nullable();
            $table->string('mobile_alt_text', 255)->nullable();
            $table->boolean('show_date')->default(true);
            $table->boolean('show_time')->default(true);
            $table->boolean('show_venue')->default(true);
            $table->boolean('show_price')->default(true);
            $table->string('primary_cta_label', 100)->nullable();
            $table->string('primary_cta_url', 2048)->nullable();
            $table->string('primary_cta_target', 20)->default('_self');
            $table->string('secondary_cta_label', 100)->nullable();
            $table->string('secondary_cta_url', 2048)->nullable();
            $table->string('secondary_cta_target', 20)->default('_self');
            $table->timestamp('starts_at')->nullable()->index();
            $table->timestamp('ends_at')->nullable()->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->string('status', 30)->default('draft')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'starts_at', 'ends_at', 'sort_order'], 'home_slides_publication_index');
        });

        Schema::create('event_memories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->unique()->constrained()->cascadeOnDelete();
            $table->longText('memory_text')->nullable();
            $table->unsignedInteger('attendance')->nullable();
            $table->json('testimonials')->nullable();
            $table->json('press_links')->nullable();
            $table->text('internal_notes')->nullable();
            $table->string('status', 30)->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('venue_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 220);
            $table->string('slug', 240)->unique();
            $table->date('album_date')->nullable()->index();
            $table->text('description')->nullable();
            $table->string('album_type', 40)->default('institutional')->index();
            $table->string('category', 100)->nullable()->index();
            $table->string('author_credit', 255)->nullable();
            $table->text('rights')->nullable();
            $table->foreignId('cover_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status', 30)->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['event_id', 'status']);
            $table->index(['album_type', 'status', 'album_date']);
        });

        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_id')->constrained('media')->restrictOnDelete();
            $table->string('title', 220)->nullable();
            $table->text('caption')->nullable();
            $table->string('alt_text', 255);
            $table->date('taken_at')->nullable();
            $table->string('author_credit', 255)->nullable();
            $table->text('rights')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['album_id', 'media_id']);
            $table->index(['album_id', 'is_visible', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
        Schema::dropIfExists('albums');
        Schema::dropIfExists('event_memories');
        Schema::dropIfExists('home_slides');
    }
};

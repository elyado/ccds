<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 170)->unique();
            $table->string('type', 80)->nullable()->index();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('area_m2', 8, 2)->nullable();
            $table->decimal('length_m', 7, 2)->nullable();
            $table->decimal('width_m', 7, 2)->nullable();
            $table->decimal('height_m', 7, 2)->nullable();
            $table->longText('accessibility')->nullable();
            $table->longText('included_equipment')->nullable();
            $table->longText('additional_equipment')->nullable();
            $table->decimal('reference_price', 10, 2)->nullable();
            $table->decimal('deposit_amount', 10, 2)->nullable();
            $table->unsignedSmallInteger('minimum_hours')->nullable();
            $table->longText('restrictions')->nullable();
            $table->longText('policies')->nullable();
            $table->json('indicative_hours')->nullable();
            $table->string('video_url', 2048)->nullable();
            $table->string('cta_label', 100)->nullable();
            $table->string('cta_url', 2048)->nullable();
            $table->string('status', 30)->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->foreignId('cover_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('floor_plan_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('technical_sheet_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('venue_layouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->restrictOnDelete();
            $table->string('name', 150);
            $table->string('slug', 170);
            $table->unsignedInteger('base_capacity');
            $table->text('description')->nullable();
            $table->longText('technical_notes')->nullable();
            $table->foreignId('diagram_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('status', 30)->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['venue_id', 'slug']);
            $table->index(['venue_id', 'status']);
        });

        Schema::create('amenity_venue', function (Blueprint $table) {
            $table->foreignId('amenity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_included')->default(true);
            $table->text('notes')->nullable();
            $table->primary(['amenity_id', 'venue_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amenity_venue');
        Schema::dropIfExists('venue_layouts');
        Schema::dropIfExists('venues');
    }
};

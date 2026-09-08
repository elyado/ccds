<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('event_categories')->nullOnDelete();
            $table->string('title', 220);
            $table->string('slug', 240)->unique();
            $table->text('summary')->nullable();
            $table->longText('body')->nullable();
            $table->string('discipline', 100)->nullable()->index();
            $table->string('event_type', 100)->nullable()->index();
            $table->string('modality', 40)->nullable()->index();
            $table->string('target_audience', 120)->nullable()->index();
            $table->string('recommended_age', 80)->nullable();
            $table->string('language', 80)->nullable();
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->foreignId('poster_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('cover_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('mobile_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('video_url', 2048)->nullable();
            $table->boolean('is_free')->default(false)->index();
            $table->decimal('reference_price_amount', 10, 2)->nullable();
            $table->string('reference_price_label', 120)->nullable();
            $table->string('cta_label', 100)->nullable();
            $table->string('cta_url', 2048)->nullable();
            $table->longText('access_notes')->nullable();
            $table->foreignId('responsible_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->longText('technical_requirements')->nullable();
            $table->longText('internal_notes')->nullable();
            $table->string('status', 30)->default('draft')->index();
            $table->timestamp('publish_starts_at')->nullable()->index();
            $table->timestamp('publish_ends_at')->nullable()->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('show_on_home')->default(false)->index();
            $table->boolean('show_in_archive')->default(true)->index();
            $table->timestamp('finished_at')->nullable()->index();
            $table->longText('cancellation_note')->nullable();
            $table->string('seo_title', 70)->nullable();
            $table->string('seo_description', 170)->nullable();
            $table->foreignId('seo_image_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'publish_starts_at', 'publish_ends_at']);
            $table->index(['show_on_home', 'status', 'sort_order']);
            $table->index(['show_in_archive', 'finished_at']);
        });

        Schema::create('event_tag', function (Blueprint $table) {
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['event_id', 'tag_id']);
        });

        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('person_id')->nullable()->constrained('people')->restrictOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->restrictOnDelete();
            $table->string('role', 150);
            $table->string('credit_text', 255)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_public')->default(true)->index();
            $table->timestamps();
            $table->index(['event_id', 'sort_order']);
            $table->unique(['event_id', 'person_id', 'role'], 'event_person_role_unique');
            $table->unique(['event_id', 'organization_id', 'role'], 'event_org_role_unique');
        });

        Schema::create('event_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_id')->constrained('media')->restrictOnDelete();
            $table->string('type', 80)->nullable()->index();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['event_id', 'media_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_documents');
        Schema::dropIfExists('event_participants');
        Schema::dropIfExists('event_tag');
        Schema::dropIfExists('events');
    }
};

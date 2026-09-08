<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('support_options', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->index();
            $table->string('title', 180);
            $table->text('description')->nullable();
            $table->string('action_type', 30);
            $table->string('cta_label', 100);
            $table->string('cta_url', 2048)->nullable();
            $table->json('suggested_amounts')->nullable();
            $table->boolean('allows_custom_amount')->default(false);
            $table->foreignId('image_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true)->index();
            $table->string('status', 30)->default('draft')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'is_visible', 'sort_order']);
        });

        Schema::create('rental_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->nullable()->constrained()->nullOnDelete();
            $table->string('requester_name', 180);
            $table->string('organization_name', 200)->nullable();
            $table->string('email', 190);
            $table->string('phone', 40)->nullable();
            $table->string('activity_name', 220);
            $table->text('activity_description')->nullable();
            $table->date('requested_date')->nullable()->index();
            $table->time('requested_start_time')->nullable();
            $table->time('requested_end_time')->nullable();
            $table->unsignedInteger('expected_attendance')->nullable();
            $table->longText('needs')->nullable();
            $table->decimal('budget_amount', 10, 2)->nullable();
            $table->string('budget_currency', 3)->default('MXN');
            $table->boolean('privacy_consent')->default(false);
            $table->timestamp('consented_at')->nullable();
            $table->string('consent_ip', 45)->nullable();
            $table->string('status', 30)->default('new')->index();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->longText('internal_notes')->nullable();
            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'assigned_to', 'created_at']);
            $table->index(['venue_id', 'requested_date']);
        });

        Schema::create('rental_request_media', function (Blueprint $table) {
            $table->foreignId('rental_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_id')->constrained()->restrictOnDelete();
            $table->string('type', 80)->nullable();
            $table->primary(['rental_request_id', 'media_id']);
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('topic', 120)->index();
            $table->string('sender_name', 180);
            $table->string('sender_email', 190);
            $table->string('sender_phone', 40)->nullable();
            $table->longText('message');
            $table->boolean('privacy_consent')->default(false);
            $table->timestamp('consented_at')->nullable();
            $table->string('consent_ip', 45)->nullable();
            $table->string('status', 30)->default('new')->index();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->longText('internal_notes')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'assigned_to', 'created_at']);
            $table->index(['sender_email', 'created_at']);
        });

        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email', 190)->unique();
            $table->string('name', 180)->nullable();
            $table->string('status', 30)->default('pending')->index();
            $table->string('confirmation_token_hash', 64)->nullable()->unique();
            $table->timestamp('privacy_consented_at');
            $table->string('consent_ip', 45)->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->string('source', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscribers');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('rental_request_media');
        Schema::dropIfExists('rental_requests');
        Schema::dropIfExists('support_options');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('venue_id')->constrained()->restrictOnDelete();
            $table->foreignId('venue_layout_id')->constrained('venue_layouts')->restrictOnDelete();
            $table->foreignId('rescheduled_from_id')->nullable()->constrained('event_schedules')->nullOnDelete();
            $table->timestamp('starts_at')->index();
            $table->timestamp('ends_at')->nullable()->index();
            $table->timestamp('doors_at')->nullable();
            $table->unsignedInteger('base_capacity_snapshot');
            $table->unsignedInteger('authorized_capacity');
            $table->unsignedInteger('production_capacity')->default(0);
            $table->unsignedInteger('complimentary_capacity')->default(0);
            $table->unsignedInteger('public_capacity');
            $table->boolean('capacity_override_authorized')->default(false);
            $table->text('capacity_override_reason')->nullable();
            $table->foreignId('capacity_override_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('capacity_override_at')->nullable();
            $table->string('access_type', 30)->default('general')->index();
            $table->decimal('price_amount', 10, 2)->nullable();
            $table->string('price_label', 120)->nullable();
            $table->string('currency', 3)->default('MXN');
            $table->string('status', 30)->default('available')->index();
            $table->boolean('show_capacity')->default(false);
            $table->boolean('show_availability')->default(true);
            $table->string('cta_label', 100)->nullable();
            $table->string('cta_url', 2048)->nullable();
            $table->text('public_note')->nullable();
            $table->text('internal_note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['event_id', 'starts_at']);
            $table->index(['venue_id', 'starts_at']);
            $table->index(['status', 'starts_at']);
            $table->index(['event_id', 'status', 'starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_schedules');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_schedule_ticket_types', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_schedule_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name', 120);
            $table->string('code', 60);
            $table->text('description')->nullable();

            $table->string('access_kind', 20)->default('paid');
            $table->decimal('price_amount', 10, 2)->nullable();
            $table->string('currency', 3)->default('MXN');

            $table->unsignedInteger('capacity_limit')->nullable();
            $table->string('sale_unit', 20)->default('person');
            $table->unsignedSmallInteger('units_per_sale')->default(1);
            $table->unsignedSmallInteger('minimum_per_purchase')->default(1);
            $table->unsignedSmallInteger('maximum_per_purchase')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['event_schedule_id', 'code']);
            $table->index(['event_schedule_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_schedule_ticket_types');
    }
};
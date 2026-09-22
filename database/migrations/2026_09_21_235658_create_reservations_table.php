<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_schedule_id')
                ->constrained()
                ->cascadeOnDelete();

            // Opcional ahora; permitirá usar categorías de acceso después.
            $table->foreignId('event_schedule_ticket_type_id')
                ->nullable()
                ->constrained('event_schedule_ticket_types')
                ->nullOnDelete();

            $table->string('folio', 40)->unique();

            $table->string('customer_name', 180);
            $table->string('customer_phone', 40)->index();
            $table->string('customer_email', 190)->nullable();

            $table->unsignedSmallInteger('quantity');
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('currency', 3)->default('MXN');

            $table->string('source', 30)->default('whatsapp')->index();
            // whatsapp, box_office, phone, web, staff

            $table->string('status', 30)->default('active')->index();
            // active, cancelled, expired

            $table->string('payment_status', 30)->default('pending')->index();
            // pending, proof_received, verified, complimentary, refunded

            $table->timestamp('expires_at')->nullable()->index();
            $table->text('internal_note')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['event_schedule_id', 'status']);
            $table->index(['event_schedule_id', 'payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
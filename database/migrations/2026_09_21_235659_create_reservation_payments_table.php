<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservation_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reservation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('method', 30)->index();
            // transfer, cash

            $table->string('status', 30)->default('pending')->index();
            // pending, verified, rejected, void

            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('MXN');

            $table->string('reference', 150)->nullable();
            $table->text('note')->nullable();

            // Podrá conectarse al comprobante cuando se termine Media Library.
            $table->foreignId('proof_media_id')
                ->nullable()
                ->constrained('media')
                ->nullOnDelete();

            $table->timestamp('received_at')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['reservation_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_payments');
    }
};
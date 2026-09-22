<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservation_check_ins', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reservation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('quantity');
            $table->timestamp('checked_in_at')->useCurrent();
            $table->text('note')->nullable();

            $table->foreignId('checked_in_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['reservation_id', 'checked_in_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_check_ins');
    }
};
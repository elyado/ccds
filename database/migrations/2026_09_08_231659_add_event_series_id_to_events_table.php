<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('events', function (Blueprint $table) {
        $table->foreign('event_series_id')
            ->references('id')
            ->on('event_series')
            ->nullOnDelete();
    });
}

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropConstrainedForeignId('event_series_id');
        });
    }
};
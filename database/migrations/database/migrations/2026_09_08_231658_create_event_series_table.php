<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('event_series', function (Blueprint $table) {
        $table->id();

        $table->foreignId('category_id')
            ->nullable()
            ->constrained('event_categories')
            ->nullOnDelete();

        $table->string('name', 180);
        $table->string('slug', 200)->unique();
        $table->string('type', 50)->default('series')->index();
        $table->text('summary')->nullable();
        $table->longText('description')->nullable();

        $table->string('status', 30)->default('draft')->index();
        $table->timestamp('starts_at')->nullable()->index();
        $table->timestamp('ends_at')->nullable()->index();

        $table->boolean('is_featured')->default(false);
        $table->boolean('show_on_home')->default(false);
        $table->unsignedInteger('sort_order')->default(0);

        $table->foreignId('created_by')->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->foreignId('updated_by')->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamps();
        $table->softDeletes();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_series');
    }
};

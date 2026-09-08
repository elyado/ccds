<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('model');
            $table->uuid('uuid')->nullable()->unique();
            $table->string('collection_name', 120)->default('default')->index();
            $table->string('name', 255);
            $table->string('file_name', 255);
            $table->string('disk', 80);
            $table->string('conversions_disk', 80)->nullable();
            $table->string('mime_type', 150)->nullable()->index();
            $table->string('extension', 20)->nullable();
            $table->unsignedBigInteger('size');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->string('alt_text', 255)->nullable();
            $table->string('credit', 255)->nullable();
            $table->text('rights')->nullable();
            $table->string('visibility', 20)->default('public')->index();
            $table->json('manipulations')->nullable();
            $table->json('custom_properties')->nullable();
            $table->json('generated_conversions')->nullable();
            $table->json('responsive_images')->nullable();
            $table->unsignedInteger('order_column')->nullable()->index();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};

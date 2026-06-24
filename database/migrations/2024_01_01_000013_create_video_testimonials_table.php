<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['upload', 'youtube'])->default('upload');
            $table->string('video_path', 255)->nullable();
            $table->string('youtube_url', 255)->nullable();
            $table->string('thumbnail_path', 255)->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(false);
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_testimonials');
    }
};

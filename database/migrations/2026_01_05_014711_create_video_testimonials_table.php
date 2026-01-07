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
        Schema::create('video_testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['upload', 'youtube'])->default('upload');
            $table->string('video_path')->nullable(); // for uploaded videos
            $table->string('youtube_url')->nullable(); // for YouTube links
            $table->string('thumbnail_path')->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(false); // needs approval from admin
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_testimonials');
    }
};

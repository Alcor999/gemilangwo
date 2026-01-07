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
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'package_id')) {
                $table->foreignId('package_id')->nullable()->constrained('packages')->onDelete('cascade')->after('user_id');
            }
            if (!Schema::hasColumn('reviews', 'title')) {
                $table->string('title')->nullable()->after('rating');
            }
            if (!Schema::hasColumn('reviews', 'content')) {
                $table->text('content')->nullable()->after('comment');
            }
            if (!Schema::hasColumn('reviews', 'helpful_count')) {
                $table->integer('helpful_count')->default(0)->after('content');
            }
            if (!Schema::hasColumn('reviews', 'unhelpful_count')) {
                $table->integer('unhelpful_count')->default(0)->after('helpful_count');
            }
            if (!Schema::hasColumn('reviews', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('unhelpful_count');
            }
            if (!Schema::hasColumn('reviews', 'is_approved')) {
                $table->boolean('is_approved')->default(false)->after('is_verified');
            }
            if (!Schema::hasColumn('reviews', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_approved');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'package_id')) $table->dropColumn('package_id');
            if (Schema::hasColumn('reviews', 'title')) $table->dropColumn('title');
            if (Schema::hasColumn('reviews', 'content')) $table->dropColumn('content');
            if (Schema::hasColumn('reviews', 'helpful_count')) $table->dropColumn('helpful_count');
            if (Schema::hasColumn('reviews', 'unhelpful_count')) $table->dropColumn('unhelpful_count');
            if (Schema::hasColumn('reviews', 'is_verified')) $table->dropColumn('is_verified');
            if (Schema::hasColumn('reviews', 'is_approved')) $table->dropColumn('is_approved');
            if (Schema::hasColumn('reviews', 'is_featured')) $table->dropColumn('is_featured');
        });
    }
};

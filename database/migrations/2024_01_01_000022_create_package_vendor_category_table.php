<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_vendor_category', function (Blueprint $table) {
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('vendor_category_id')->constrained('vendor_categories')->onDelete('cascade');
            $table->foreignId('default_vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->primary(['package_id', 'vendor_category_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_vendor_category');
    }
};

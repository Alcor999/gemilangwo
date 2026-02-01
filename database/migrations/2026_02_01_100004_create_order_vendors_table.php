<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->foreignId('vendor_category_id')->constrained('vendor_categories')->onDelete('cascade');
            $table->string('vendor_name'); // Snapshot
            $table->string('vendor_category_name'); // Snapshot
            $table->decimal('price', 12, 2); // Snapshot at order time
            $table->timestamps();

            $table->unique(['order_id', 'vendor_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_vendors');
    }
};

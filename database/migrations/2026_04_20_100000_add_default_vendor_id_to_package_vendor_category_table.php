<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('package_vendor_category', function (Blueprint $table) {
            $table->foreignId('default_vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('package_vendor_category', function (Blueprint $table) {
            $table->dropForeign(['default_vendor_id']);
            $table->dropColumn('default_vendor_id');
        });
    }
};

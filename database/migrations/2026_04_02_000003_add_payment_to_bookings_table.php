<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('payment_method', ['qris', 'etoll'])->nullable()->after('status');
            $table->string('payment_status', 20)->default('paid')->after('payment_method');
            $table->string('etoll_number', 20)->nullable()->after('payment_status');
            // is_completed sudah ditambahkan oleh migration sebelumnya
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status', 'etoll_number']);
        });
    }
};

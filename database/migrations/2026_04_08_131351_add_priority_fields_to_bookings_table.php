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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('priority_need')->nullable()->after('seat_number')->comment('low, high, other');
            $table->boolean('is_priority')->default(false)->after('priority_need');
            $table->boolean('is_boarded')->default(false)->after('is_completed')->comment('True jika penumpang sudah memvalidasi geofence di titik kumpul');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['priority_need', 'is_priority', 'is_boarded']);
        });
    }
};

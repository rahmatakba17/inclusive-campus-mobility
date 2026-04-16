<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->integer('bus_number')->nullable()->after('name'); // nomor armada 1-13
            // trip_status sudah ditambahkan oleh migration sebelumnya
            $table->decimal('current_lat', 10, 8)->nullable();
            $table->decimal('current_lng', 11, 8)->nullable();
            $table->string('current_terminal', 20)->nullable(); // kode terminal
            $table->timestamp('departed_at')->nullable(); // kapan mulai jalan
        });
    }

    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn(['bus_number', 'current_lat', 'current_lng', 'current_terminal', 'departed_at']);
        });
    }
};

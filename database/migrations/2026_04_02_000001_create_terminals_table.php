<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terminals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->unique(); // PERINTIS, TAMAL, ANTANG, PALLANGGA, GOWA
            $table->double('lat', 15, 8);
            $table->double('lng', 15, 8);
            $table->integer('order'); // urutan di rute (1 = awal, N = akhir)
            $table->enum('type', ['origin', 'stop', 'destination'])->default('stop');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terminals');
    }
};

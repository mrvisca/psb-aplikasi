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
        Schema::create('master_mapels', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('jurusan_id');
            $table->string('name');
            $table->enum('kelompok',['Kelompok A','Kelompok B','Kelompok C'])->default('Kelompok A');
            $table->enum('type',['Nilai Pengetahuan','Nilai Keterampilan'])->default('Nilai Pengetahuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_mapels');
    }
};

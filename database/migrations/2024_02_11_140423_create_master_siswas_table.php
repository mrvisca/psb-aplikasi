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
        Schema::create('master_siswas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nis');
            $table->bigInteger('user_id');
            $table->bigInteger('jurusan_id')->default(0);
            $table->enum('jenkel',['laki-laki','perempuan'])->default('laki-laki');
            $table->integer('kelas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_siswas');
    }
};

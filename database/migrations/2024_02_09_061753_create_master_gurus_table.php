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
        Schema::create('master_gurus', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->bigInteger('nip');
            $table->enum('jenkel',['laki-laki','perempuan'])->default('laki-laki');
            $table->string('jabatan');
            $table->bigInteger('telpon');
            $table->string('photo')->nullable();
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_gurus');
    }
};

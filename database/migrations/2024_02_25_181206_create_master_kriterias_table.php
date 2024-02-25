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
        Schema::create('master_kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('name');
            $table->string('attribute');
            $table->integer('bobot');
            $table->string('kurikulum');
            $table->timestamps();
        });

        Schema::table('tahun_ajars', function (Blueprint $table) {
            $table->dropColumn('kurikulum');
            $table->string('semester')->default('Ganjil')->after('tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_kriterias');
    }
};

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
        Schema::create('proyek_penggajians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id');
            $table->string('nama_periode');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->tinyInteger('jam_kerja');
            $table->text('keterangan')->nullable();
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyek_penggajians');
    }
};

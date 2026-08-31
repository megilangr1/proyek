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
        Schema::create('proyek_pekerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id');
            $table->string('nama_pekerja');
            $table->string('nomor_hp');
            $table->string('status_jabatan');
            $table->decimal('tarif_harian');
            $table->decimal('tarif_overtime');
            $table->text('catatan')->nullable();
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
        Schema::dropIfExists('proyek_pekerjas');
    }
};

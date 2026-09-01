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
        Schema::create('proyek_penggajian_pekerja_haris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_penggajian_pekerja_id');

            $table->date('tanggal');

            $table->decimal('hari_normal');
            $table->decimal('jam_overtime');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyek_penggajian_pekerja_haris');
    }
};

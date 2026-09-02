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
        Schema::create('proyek_penggajian_pekerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_penggajian_id');
            $table->foreignId('proyek_pekerja_id');

            $table->string('jabatan');
            $table->decimal('tarif_harian');
            $table->decimal('tarif_overtime');

            $table->decimal('total_hari');
            $table->decimal('total_overtime');

            $table->decimal('gaji_normal')->default(0);
            $table->decimal('upah_overtime')->default(0);

            $table->decimal('bonus')->default(0);
            $table->decimal('potongan')->default(0);
            $table->decimal('kasbon')->default(0);

            $table->decimal('total_bersih');

            $table->tinyInteger('status_bayar')->default(1);
            $table->date('tanggal_bayar')->nullable();

            $table->text('keterangan')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyek_penggajian_pekerjas');
    }
};

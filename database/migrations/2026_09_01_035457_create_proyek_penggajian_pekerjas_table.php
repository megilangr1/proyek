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
            $table->decimal('tarif_harian', 15, 2);
            $table->decimal('tarif_overtime', 15, 2);

            $table->decimal('total_hari', 15, 2);
            $table->decimal('total_overtime', 15, 2);

            $table->decimal('gaji_normal', 15, 2)->default(0);
            $table->decimal('upah_overtime', 15, 2)->default(0);

            $table->decimal('bonus', 15, 2)->default(0);
            $table->decimal('potongan', 15, 2)->default(0);
            $table->decimal('kasbon', 15, 2)->default(0);

            $table->decimal('total_bersih', 15, 2);

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

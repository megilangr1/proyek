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
        Schema::create('proyek_pengeluarans', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('proyek_id');
            $table->date('tanggal');
            $table->tinyInteger('kategori');
            $table->string('nama_item');
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyek_pengeluarans');
    }
};

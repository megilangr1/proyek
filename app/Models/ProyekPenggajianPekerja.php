<?php

namespace App\Models;

use App\Enums\StatusBayar;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'proyek_penggajian_id',
    'proyek_pekerja_id',
    'jabatan',
    'tarif_harian',
    'tarif_lembur',
    'total_hari',
    'total_overtime',
    'gaji_normal',
    'upah_overtime',
    'bonus',
    'potongan',
    'kasbon',
    'total_bersih',
    'status_bayar',
    'tanggal_bayar',
    'keterangan',
])]
class ProyekPenggajianPekerja extends Model
{
    use SoftDeletes;

    protected $casts = [
        'tarif_harian' => 'decimal:2',
        'tarif_lembur' => 'decimal:2',
        'total_hari' => 'decimal:2',
        'total_overtime' => 'decimal:2',
        'gaji_normal' => 'decimal:2',
        'upah_overtime' => 'decimal:2',
        'bonus' => 'decimal:2',
        'potongan' => 'decimal:2',
        'kasbon' => 'decimal:2',
        'total_bersih' => 'decimal:2',

        'status_bayar' => StatusBayar::class,
        'tanggal_bayar' => 'date',
    ];

    public function proyekPenggajian(): BelongsTo
    {
        return $this->belongsTo(ProyekPenggajian::class);
    }

    public function proyekPekerja(): BelongsTo
    {
        return $this->belongsTo(ProyekPekerja::class);
    }
}

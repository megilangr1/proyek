<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'proyek_penggajian_pekerja_id',
    'tanggal',
    'hari_normal',
    'jam_overtime',
])]
class ProyekPenggajianPekerjaHari extends Model
{
    use SoftDeletes;

    protected $casts = [
        'tanggal' => 'date',
        'hari_normal' => 'decimal:2',
        'jam_overtime' => 'decimal:2',
    ];

    public function proyekPenggajianPekerja(): BelongsTo
    {
        return $this->belongsTo(ProyekPenggajianPekerja::class);
    }
}

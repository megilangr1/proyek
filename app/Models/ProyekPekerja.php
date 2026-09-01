<?php

namespace App\Models;

use App\Enums\StatusPekerja;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'proyek_id',
    'nama_pekerja',
    'nomor_hp',
    'status_jabatan',
    'tarif_harian',
    'tarif_overtime',
    'catatan',
    'status',
])]
class ProyekPekerja extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'tarif_harian' => 'decimal:2',
        'tarif_overtime' => 'decimal:2',
        'status' => StatusPekerja::class,
    ];

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class);
    }
}

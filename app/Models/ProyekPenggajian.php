<?php

namespace App\Models;

use App\Enums\StatusPenggajian;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'proyek_id',
    'nama_periode',
    'periode_mulai',
    'periode_selesai',
    'jam_kerja',
    'keterangan',
    'status',
])]
class ProyekPenggajian extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
        'jam_kerja' => 'integer',
        'status' => StatusPenggajian::class,
    ];

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class);
    }

    public function proyekPenggajianPekerja(): HasMany
    {
        return $this->hasMany(ProyekPenggajianPekerja::class);
    }
}

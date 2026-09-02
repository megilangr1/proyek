<?php

namespace App\Models;

use App\Enums\StatusProyek;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'kode_proyek',
    'nama_proyek',
    'pemilik',
    'lokasi',
    'tanggal_mulai',
    'tanggal_selesai',
    'status',
])]
class Proyek extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'status' => StatusProyek::class,
    ];

    public function proyekPekerja(): HasMany
    {
        return $this->hasMany(ProyekPekerja::class);
    }

    public function proyekPenggajian(): HasMany
    {
        return $this->hasMany(ProyekPenggajian::class);
    }

    public function proyekPengeluaran(): HasMany
    {
        return $this->hasMany(ProyekPengeluaran::class);
    }
}

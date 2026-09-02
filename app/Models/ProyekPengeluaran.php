<?php

namespace App\Models;

use App\Enums\KategoriPengeluaran;
use App\Enums\StatusPengeluaran;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'proyek_id',
    'tanggal',
    'kategori',
    'nama_item',
    'nominal',
    'keterangan',
    'status',
])]
class ProyekPengeluaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
        'kategori' => KategoriPengeluaran::class,
        'status' => StatusPengeluaran::class,
    ];

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class);
    }
}

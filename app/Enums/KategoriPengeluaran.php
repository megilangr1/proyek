<?php

namespace App\Enums;

enum KategoriPengeluaran: int
{
    case BAHAN_BAKU = 1;
    case SEMEN = 2;
    case PERALATAN = 3;
    case BAHAN_LAIN = 4;
    case UPAH = 5;
    case LAINNYA = 6;

    public function label(): string
    {
        return match ($this) {
            self::BAHAN_BAKU => 'Bahan Baku',
            self::SEMEN => 'Semen',
            self::PERALATAN => 'Peralatan',
            self::BAHAN_LAIN => 'Bahan Lain',
            self::UPAH => 'Upah',
            self::LAINNYA => 'Lainnya',
        };
    }

    public static function toSelectArray(): array
    {
        return array_column(array_map(fn ($item): array => [
            'value' => $item->value,
            'label' => $item->label(),
        ], self::cases()), 'label', 'value');
    }
}

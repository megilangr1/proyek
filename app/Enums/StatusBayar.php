<?php

namespace App\Enums;

enum StatusBayar: int
{
    case AKTIF = 1;
    case NONAKTIF = 2;

    public function label(): string
    {
        return match ($this) {
            self::AKTIF => 'Belum di-Bayar',
            self::NONAKTIF => 'Sudah di-Bayar',
        };
    }

    public static function toSelectArray(): array
    {
        return array_column(array_map(fn($item) => [
            'value' => $item->value,
            'label' => $item->label(),
        ], self::cases()), 'label', 'value');
    }
}

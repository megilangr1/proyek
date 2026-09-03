<?php

namespace App\Enums;

enum StatusBayar: int
{
    case BELUM = 1;
    case SUDAH = 2;

    public function label(): string
    {
        return match ($this) {
            self::BELUM => 'Belum di-Bayar',
            self::SUDAH => 'Sudah di-Bayar',
        };
    }

    public static function toSelectArray(): array
    {
        return array_column(array_map(fn ($item) => [
            'value' => $item->value,
            'label' => $item->label(),
        ], self::cases()), 'label', 'value');
    }
}

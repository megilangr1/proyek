<?php

namespace App\Enums;

enum StatusPengeluaran: int
{
    case AKTIF = 1;
    case NONAKTIF = 2;

    public function label(): string
    {
        return match ($this) {
            self::AKTIF => 'Aktif',
            self::NONAKTIF => 'Non-Aktif',
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

<?php

namespace App\Enums;

enum StatusBilling: string
{
    case UNPAID = 'UNPAID';
    case PAID = 'PAID';
    case PARTIAL = 'PARTIAL';
    case CANCELLED = 'CANCELLED';

    public function label(): string
    {
        return match ($this) {
            self::UNPAID => 'Belum Bayar',
            self::PAID => 'Sudah Bayar',
            self::PARTIAL => 'Cicil',
            self::CANCELLED => 'Batal',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn($gender) => [
                'value' => $gender->value,
                'label' => $gender->label(),
            ],
            self::cases()
        );
    }
}

<?php

namespace App\Enums;

enum StatusBilling: string
{
    case UNPAID = 'UNPAID';
    case PAID = 'PAID';
    case PARTIAL = 'PARTIAL';
    case OVERDUE = 'OVERDUE';

    public function label(): string
    {
        return match ($this) {
            self::UNPAID => 'Belum Lunas',
            self::PAID => 'Lunas',
            self::PARTIAL => 'Cicil',
            self::OVERDUE => 'Jatuh Tempo',
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

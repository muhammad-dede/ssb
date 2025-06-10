<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'CASH';
    case TRANSFER = 'TRANSFER';

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Tunai',
            self::TRANSFER => 'Transfer',
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

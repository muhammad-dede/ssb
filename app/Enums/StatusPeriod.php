<?php

namespace App\Enums;

enum StatusPeriod: string
{
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Aktif',
            self::INACTIVE => 'Nonaktif',
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

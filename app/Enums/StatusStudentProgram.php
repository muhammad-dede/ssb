<?php

namespace App\Enums;

enum StatusStudentProgram: string
{
    case UNREGISTERED = 'UNREGISTERED';
    case REGISTERED = 'REGISTERED';

    public function label(): string
    {
        return match ($this) {
            self::UNREGISTERED => 'Belum Terdaftar',
            self::REGISTERED => 'Terdaftar',
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

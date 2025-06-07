<?php

namespace App\Enums;

enum DominantFoot: string
{
    case RIGHT = 'RIGHT';
    case LEFT = 'LEFT';
    case BOTH = 'BOTH';

    public function label(): string
    {
        return match ($this) {
            self::RIGHT => 'Kanan',
            self::LEFT => 'Kiri',
            self::BOTH => 'Keduanya',
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

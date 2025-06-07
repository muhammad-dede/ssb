<?php

namespace App\Enums;

enum Attendance: string
{
    case PRESENT = 'PRESENT';
    case ABSENT = 'ABSENT';
    case EXCUSED = 'EXCUSED';

    public function label(): string
    {
        return match ($this) {
            self::PRESENT => 'Hadir',
            self::ABSENT => 'Tidak Hadir',
            self::EXCUSED => 'Izin',
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

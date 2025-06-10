<?php

namespace App\Enums;

enum Variant: string
{
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';
    case UNPAID = 'UNPAID';
    case PENDING = 'PENDING';
    case PAID = 'PAID';
    case FAILED = 'FAILED';
    case EXPIRED = 'EXPIRED';
    case CANCELLED = 'CANCELLED';
    case PARTIAL = 'PARTIAL';
    case OVERDUE = 'OVERDUE';
    case INVALID = 'INVALID';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'default',
            self::INACTIVE => 'destructive',
            self::UNPAID => 'secondary',
            self::PENDING => 'outline',
            self::PAID => 'default',
            self::FAILED => 'destructive',
            self::EXPIRED => 'destructive',
            self::CANCELLED => 'outline',
            self::PARTIAL => 'outline',
            self::OVERDUE => 'destructive',
            self::INVALID => 'destructive',
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

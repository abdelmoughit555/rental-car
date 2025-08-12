<?php

namespace App\Enums\Cars;

enum CarStatus: int
{
    case DRAFT = 0;
    case ACTIVE = 1;
    case INACTIVE = 2;
    case MAINTENANCE = 3;
    case RENTED = 4;
    case RESERVED = 5;
    case PENDING_APPROVAL = 6;
    case REJECTED = 7;

    public function toName(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::MAINTENANCE => 'Maintenance',
            self::RENTED => 'Rented',
            self::RESERVED => 'Reserved',
            self::PENDING_APPROVAL => 'Pending Approval',
            self::REJECTED => 'Rejected',
        };
    }

    public function toColor(): string
    {
        return match ($this) {
            self::ACTIVE => 'green',
            self::DRAFT => 'gray',
            self::INACTIVE => 'red',
            self::MAINTENANCE => 'yellow',
            self::RENTED => 'blue',
            self::RESERVED => 'orange',
            self::PENDING_APPROVAL => 'purple',
            self::REJECTED => 'red',
        };
    }

    public static function toSelectArray(): array
    {
        return array_map(
            fn ($status) => [
                'id' => $status->value,
                'name' => $status->toName(),
                'color' => $status->toColor()
            ],
            self::cases()
        );
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

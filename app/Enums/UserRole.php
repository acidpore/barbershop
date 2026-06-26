<?php

namespace App\Enums;

enum UserRole: string
{
    case Owner = 'owner';
    case Kasir = 'kasir';
    case Barber = 'barber';

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

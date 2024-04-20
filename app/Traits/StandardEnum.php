<?php

namespace App\Traits;

trait StandardEnum
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }

    public static function toArray(): array
    {
        return self::values();
    }

    public static  function toString(): string
    {
        return implode(',', self::values());
    }

    public function equals(self $enum): bool
    {
        return $this->value === $enum->value;
    }
}

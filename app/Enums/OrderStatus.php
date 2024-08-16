<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Fulfilled = 'fulfilled';
    case Canceled = 'canceled';
    case Pending = 'pending';

    public function color(): string
    {
        return match($this) {
            self::Fulfilled => 'bg-success',
            self::Canceled => 'bg-danger',
        };
    }
}

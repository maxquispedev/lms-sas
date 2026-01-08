<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentGateway: string
{
    case Manual = 'Manual';
    case Culqi = 'Culqi';

    /**
     * Get the color for the badge.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::Manual => 'gray',
            self::Culqi => 'primary',
        };
    }
}


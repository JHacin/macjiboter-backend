<?php

namespace App\Utilities;

class CurrencyFormat
{
    public static function format(float $amount): string
    {
        $formatted = number_format($amount, 2, ',', '.');

        return "$formatted €";
    }
}

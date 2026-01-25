<?php

namespace App\Support;

class Helpers
{
    public static function generateTransactionReference(): string
    {
        return 'TRX-' . strtoupper(uniqid());
    }
}

<?php

namespace App\Models;

// clears status scope in admin
class UnscopedCat extends Cat
{
    /**
     * @inheritDoc
     */
    protected static function booted(): void
    {
        static::withoutGlobalScopes();
    }
}

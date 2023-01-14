<?php

namespace App\Models\Traits;

trait ClearsGlobalScopes
{
    /**
     * Remove global scopes such as only returning cats with is_active=1 (used in admin).
     */
    public function withoutGlobalScopes(): void
    {
        static::$globalScopes = [];
    }
}

<?php

namespace App\Settings;

use Setting;

class Settings
{
    public const VALUE_TRUE = '1';
    public const VALUE_FALSE = '0';

    public const KEY_ENABLE_EMAILS = 'enable_emails';
    public const KEY_ENABLE_MAILING_LISTS = 'enable_mailing_lists';

    public static function get(string $key, string $fallback = ''): string
    {
        return Setting::get($key) ?? $fallback;
    }

    public static function set(string $key, string $value): string
    {
        return Setting::set($key, $value);
    }

    public static function hasValueTrue(string $key): bool
    {
        return self::get($key) === self::VALUE_TRUE;
    }

    public static function setValueFalse(string $key): bool
    {
        return self::set($key, self::VALUE_FALSE);
    }
}

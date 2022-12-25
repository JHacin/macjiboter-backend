<?php

namespace App\Utilities;

use DateInterval;

class AgeFormat
{
    public static function formatToAgeString(DateInterval $age): string
    {
        $format = self::getFormatBasedOnIntervalValues($age->y, $age->m, $age->d);

        return $age->format($format);
    }

    protected static function getFormatBasedOnIntervalValues(int $years, int $months, int $days): string
    {
        $yearsTranslated = trans_choice('date.years', $years);
        $monthsTranslated = trans_choice('date.months', $months);
        $daysTranslated = trans_choice('date.days', $days);

        if ($years > 0 && $months > 0) {
            return "%y {$yearsTranslated} in %m {$monthsTranslated}";
        }

        if ($years > 0) {
            return "%y {$yearsTranslated}";
        }

        if ($months > 0) {
            return "%m {$monthsTranslated}";
        }

        if ($days > 0) {
            return "%d {$daysTranslated}";
        }

        return '1 dan';
    }
}

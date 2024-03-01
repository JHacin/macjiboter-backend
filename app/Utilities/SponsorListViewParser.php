<?php

namespace App\Utilities;

use App\Models\PersonData;
use App\Models\SpecialSponsorship;
use App\Models\Sponsorship;
use Illuminate\Support\Collection;

class SponsorListViewParser
{
    /**
     * @param  Collection|Sponsorship[]|SpecialSponsorship[]  $sponsorships
     */
    public static function prepareViewData($sponsorships): array
    {
        $anonymousCount = 0;
        $identifiedSponsors = [];

        foreach ($sponsorships as $sponsorship) {
            if (self::isConsideredAnonymous($sponsorship)) {
                $anonymousCount++;
            } else {
                $identifiedSponsors[] = [
                    'id' => $sponsorship->sponsor->id,
                    'first_name' => $sponsorship->sponsor->first_name,
                    'city' => $sponsorship->sponsor->city,
                ];
            }

        }

        return [
            'anonymousCount' => $anonymousCount,
            'identifiedSponsors' => $identifiedSponsors,
        ];
    }

    /**
     * @param  Sponsorship|SpecialSponsorship  $sponsorship
     */
    protected static function isConsideredAnonymous($sponsorship): bool
    {
        return $sponsorship->is_anonymous || self::isMissingAllDisplayableProperties($sponsorship->sponsor);
    }

    protected static function isMissingAllDisplayableProperties(PersonData $sponsor): bool
    {
        return ! $sponsor->first_name && ! $sponsor->city;
    }
}

<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Venturecraft\Revisionable\Revision;

/**
 * App\Models\UnscopedCat
 *
 * Clears status scope in admin.
 *
 * @property int $id
 * @property string $name
 * @property int|null $gender
 * @property int $status
 * @property string $story_short
 * @property string|null $story
 * @property Carbon|null $date_of_arrival_mh
 * @property Carbon|null $date_of_birth
 * @property bool $is_group
 * @property bool $is_published
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $slug
 * @property int|null $location_id
 * @property-read array $crud_photos_array
 * @property-read string $gender_label
 * @property-read string $name_and_id
 * @property-read string $status_label
 * @property-read CatLocation|null $location
 * @property-read Collection<int, CatPhoto> $photos
 * @property-read int|null $photos_count
 * @property-read Collection<int, Revision> $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read Collection<int, SponsorshipMessage> $sponsorshipMessages
 * @property-read int|null $sponsorship_messages_count
 * @property-read Collection<int, Sponsorship> $sponsorships
 * @property-read int|null $sponsorships_count
 *
 * @method static Builder|UnscopedCat newModelQuery()
 * @method static Builder|UnscopedCat newQuery()
 * @method static Builder|UnscopedCat query()
 * @method static Builder|UnscopedCat whereCreatedAt($value)
 * @method static Builder|UnscopedCat whereDateOfArrivalMh($value)
 * @method static Builder|UnscopedCat whereDateOfBirth($value)
 * @method static Builder|UnscopedCat whereGender($value)
 * @method static Builder|UnscopedCat whereId($value)
 * @method static Builder|UnscopedCat whereIsGroup($value)
 * @method static Builder|UnscopedCat whereIsPublished($value)
 * @method static Builder|UnscopedCat whereLocationId($value)
 * @method static Builder|UnscopedCat whereName($value)
 * @method static Builder|UnscopedCat whereSlug($value)
 * @method static Builder|UnscopedCat whereStatus($value)
 * @method static Builder|UnscopedCat whereStory($value)
 * @method static Builder|UnscopedCat whereStoryShort($value)
 * @method static Builder|UnscopedCat whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class UnscopedCat extends Cat
{
    /**
     * {@inheritDoc}
     */
    protected static function booted(): void
    {
        static::withoutGlobalScopes();
    }
}

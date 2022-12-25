<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\SponsorshipMessageFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\SponsorshipMessage
 *
 * @property int $id
 * @property int|null $message_type_id
 * @property int|null $cat_id
 * @property int|null $sponsor_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Cat|null $cat
 * @property-read SponsorshipMessageType|null $messageType
 * @property-read PersonData|null $sponsor
 * @method static Builder|SponsorshipMessage newModelQuery()
 * @method static Builder|SponsorshipMessage newQuery()
 * @method static Builder|SponsorshipMessage query()
 * @method static Builder|SponsorshipMessage whereCatId($value)
 * @method static Builder|SponsorshipMessage whereCreatedAt($value)
 * @method static Builder|SponsorshipMessage whereId($value)
 * @method static Builder|SponsorshipMessage whereMessageTypeId($value)
 * @method static Builder|SponsorshipMessage whereSponsorId($value)
 * @method static Builder|SponsorshipMessage whereUpdatedAt($value)
 * @method static SponsorshipMessageFactory factory(...$parameters)
 * @mixin Eloquent
 */
class SponsorshipMessage extends Model
{
    use CrudTrait, HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sponsorship_messages';
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function messageType(): BelongsTo
    {
        return $this->belongsTo(SponsorshipMessageType::class, 'message_type_id');
    }

    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(PersonData::class, 'sponsor_id');
    }

    public function cat(): BelongsTo
    {
        return $this->belongsTo(Cat::class, 'cat_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}

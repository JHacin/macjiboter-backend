<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\CatLocationFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Venturecraft\Revisionable\Revision;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Models\CatLocation
 *
 * @property int $id
 * @property string $name
 * @property string|null $address
 * @property string|null $zip_code
 * @property string|null $city
 * @property string|null $country
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Cat[] $cats
 * @property-read int|null $cats_count
 * @property-read Collection|Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @method static Builder|CatLocation newModelQuery()
 * @method static Builder|CatLocation newQuery()
 * @method static Builder|CatLocation query()
 * @method static Builder|CatLocation whereAddress($value)
 * @method static Builder|CatLocation whereCity($value)
 * @method static Builder|CatLocation whereCountry($value)
 * @method static Builder|CatLocation whereCreatedAt($value)
 * @method static Builder|CatLocation whereId($value)
 * @method static Builder|CatLocation whereName($value)
 * @method static Builder|CatLocation whereUpdatedAt($value)
 * @method static Builder|CatLocation whereZipCode($value)
 * @method static CatLocationFactory factory(...$parameters)
 * @mixin Eloquent
 */
class CatLocation extends Model
{
    use CrudTrait, RevisionableTrait, HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'cat_locations';
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

    /**
     * Get the cats that are on this location.
     *
     * @return HasMany
     */
    public function cats(): HasMany
    {
        return $this->hasMany(Cat::class, 'location_id');
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

    /*
    |--------------------------------------------------------------------------
    | CRUD-RELATED FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function identifiableName(): string
    {
        return $this->name;
    }
}

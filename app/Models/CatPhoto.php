<?php

namespace App\Models;

use App\Services\CatPhotoService;
use Database\Factories\CatPhotoFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/**
 * App\Models\CatPhoto
 *
 * @property int $id
 * @property string $filename
 * @property string|null $caption
 * @property int $index
 * @property array $sizes
 * @property int $cat_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Cat $cat
 * @property-read string $url
 * @method static CatPhotoFactory factory(...$parameters)
 * @method static Builder|CatPhoto newModelQuery()
 * @method static Builder|CatPhoto newQuery()
 * @method static Builder|CatPhoto query()
 * @method static Builder|CatPhoto whereCaption($value)
 * @method static Builder|CatPhoto whereCatId($value)
 * @method static Builder|CatPhoto whereCreatedAt($value)
 * @method static Builder|CatPhoto whereFilename($value)
 * @method static Builder|CatPhoto whereId($value)
 * @method static Builder|CatPhoto whereIndex($value)
 * @method static Builder|CatPhoto whereSizes($value)
 * @method static Builder|CatPhoto whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CatPhoto extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | CONSTANTS
    |--------------------------------------------------------------------------
    */

    public const SCOPE_ORDER_BY_INDEX_ASC = 'catPhoto_orderByIndexAsc';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'cat_photos';

    protected $guarded = ['id'];

    protected $appends = ['url'];

    protected $casts = [
        'sizes' => 'array',
    ];

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
     * Get the cat this photo belongs to.
     */
    public function cat(): BelongsTo
    {
        return $this->belongsTo(Cat::class, 'cat_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::addGlobalScope(self::SCOPE_ORDER_BY_INDEX_ASC, function (Builder $builder) {
            $builder->orderBy('index');
        });

        static::deleted(function (CatPhoto $photo) {
            CatPhotoService::deleteFiles($photo);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getUrlAttribute(): string
    {
        return CatPhotoService::getUrl($this->filename);
    }

    /*
     * Append a full URL to each size.
     *
     * The sizes column is stored as a JSON-like string in the DB,
     * so we first need to convert it to an array,
     * and then modify each member (size).
     */
    protected function sizes(): Attribute
    {
        return Attribute::make(
            get: fn ($string) => Arr::map((array) json_decode($string), function ($size) {
                $size->url = CatPhotoService::getUrl($size->filename);

                return $size;
            }),
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}

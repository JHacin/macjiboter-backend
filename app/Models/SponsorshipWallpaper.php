<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Storage;

/**
 * App\Models\SponsorshipWallpaper
 *
 * @property int $id
 * @property Carbon $month_and_year
 * @property string $file_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|SponsorshipWallpaper newModelQuery()
 * @method static Builder|SponsorshipWallpaper newQuery()
 * @method static Builder|SponsorshipWallpaper query()
 * @method static Builder|SponsorshipWallpaper whereCreatedAt($value)
 * @method static Builder|SponsorshipWallpaper whereFilePath($value)
 * @method static Builder|SponsorshipWallpaper whereId($value)
 * @method static Builder|SponsorshipWallpaper whereMonthAndYear($value)
 * @method static Builder|SponsorshipWallpaper whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SponsorshipWallpaper extends Model
{
    use CrudTrait, HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sponsorship_wallpapers';

    protected $guarded = ['id'];

    protected $casts = [
        'month_and_year' => 'date',
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

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::deleting(function (SponsorshipWallpaper $wallpaper) {
            Storage::disk('s3')->delete($wallpaper->file_path);
        });
    }

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
    public function setFilePathAttribute($value): void
    {
        $path = config('filesystems.disks.s3.public_folder') . '/ozadja';

        // e.g. apr24_1718479386.zip
        $date = $this->month_and_year;
        $fileName = strtolower($date->translatedFormat('M')) . $date->format('y') . '_' . time() . '.zip';

        $this->uploadFileToDisk(
            value: $value,
            attribute_name: 'file_path',
            disk: 's3',
            destination_path: $path,
            fileName: $fileName
        );

        // allows admins to download the file via the edit form.
        Storage::disk('s3')->setVisibility($path . '/' . $fileName, 'public');
    }
}

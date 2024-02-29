<?php

namespace App\Services;

use App\Models\Cat;
use App\Models\CatPhoto;
use Image;
use ImageOptimizer;
use Storage;

class CatPhotoService
{
    const PATH_ROOT = 'muce/slike';

    public static function create(Cat $cat, array $attributes): CatPhoto
    {
        $images = static::createImages($attributes['url']);

        $photo = new CatPhoto;
        $photo->cat_id = $cat->id;
        $photo->filename = $images['lg']['filename'];
        $photo->index = $attributes['index'];
        $photo->caption = $attributes['caption'];
        $photo->sizes = [
            'md' => $images['md'],
            'sm' => $images['sm'],
        ];
        $photo->save();

        return $photo;
    }

    private static function createImages(string $base64): array
    {
        $images = [
            'lg' => ['name' => 'lg', 'width' => 1600],
            'md' => ['name' => 'md', 'width' => 960],
            'sm' => ['name' => 'sm', 'width' => 480],
        ];
        $disk = Storage::disk('public');
        $fileNameBase = md5($base64.time());

        $image = Image::make($base64)->encode('jpg', 80);

        foreach ($images as $size => $sizeConfig) {
            $image->widen($sizeConfig['width'], function ($constraint) {
                $constraint->upsize();
            });

            $filename = $fileNameBase.'_'.$sizeConfig['name'].'.jpg';
            $images[$size]['filename'] = $filename;

            $path = CatPhotoService::getFullPath($filename);
            $image->save($disk->path($path));
            ImageOptimizer::optimize($disk->path($path));
        }

        return $images;
    }

    public static function getFullPath(string $filename): string
    {
        return static::PATH_ROOT.'/'.$filename;
    }

    public static function getUrl(string $filename): string
    {
        return url(Storage::url(self::getFullPath($filename)));
    }

    public static function deleteFiles(CatPhoto $photo): void
    {
        $fileNames = [$photo->filename];

        foreach ($photo->sizes as $size) {
            $fileNames[] = $size->filename;
        }

        foreach ($fileNames as $fileName) {
            Storage::disk('public')->delete(static::getFullPath($fileName));
        }
    }
}

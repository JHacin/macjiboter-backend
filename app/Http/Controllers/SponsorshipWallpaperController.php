<?php

namespace App\Http\Controllers;

use App\Models\SponsorshipWallpaper;
use Carbon\Carbon;
use Storage;

class SponsorshipWallpaperController extends Controller
{
    public function getAll()
    {
        $wallpapers = SponsorshipWallpaper::orderBy('month_and_year', 'desc')->get();

        return response()->json(['data' => $wallpapers]);
    }

    public function download(SponsorshipWallpaper $wallpaper)
    {
        $date = Carbon::parse($wallpaper->month_and_year);
        $fileName = 'ozadja-' . $date->translatedFormat('M') . $date->format('y') . '.zip';
        $headers = ['Access-Control-Expose-Headers' => 'Content-Disposition'];

        return Storage::disk('s3')->download($wallpaper->file_path, $fileName, $headers);
    }
}

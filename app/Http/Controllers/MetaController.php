<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use Illuminate\Http\JsonResponse;

class MetaController extends Controller
{
    /**
     * Returns the data needed on the homepage.
     */
    public function homePage(): JsonResponse
    {
        $heroCats = Cat::where('is_group', false)
            ->inRandomOrder()
            ->get()
            ->slice(0, 4);

        return response()->json([
            'heroCats' => $heroCats,
        ]);
    }
}

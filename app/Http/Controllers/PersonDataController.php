<?php

namespace App\Http\Controllers;

use App\Models\PersonData;
use Illuminate\Http\JsonResponse;

class PersonDataController extends Controller
{
    public function getOne(PersonData $personData): JsonResponse
    {
        return response()->json($personData);
    }
}

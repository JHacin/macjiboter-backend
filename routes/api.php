<?php

use App\Http\Controllers\CatsController;
use App\Http\Controllers\CatSponsorshipController;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PersonDataController;
use App\Http\Controllers\SpecialSponsorshipController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/meta/home', [MetaController::class, 'homePage']);

Route::get('/cats', [CatsController::class, 'getAll']);
Route::get('/cats/{cat}', [CatsController::class, 'getOne']);

Route::post('/cats/{cat:id}/adopt', [CatSponsorshipController::class, 'submitForm']);

Route::post('/special-sponsorships', [SpecialSponsorshipController::class, 'submitForm']);
Route::get('/special-sponsorships/recent', [SpecialSponsorshipController::class, 'getRecent']);

Route::get('/person-data/{personData}', [PersonDataController::class, 'getOne']);

Route::get('/news', [NewsController::class, 'getAll']);

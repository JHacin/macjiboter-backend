<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Utilities\SponsorListViewParser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatsController extends Controller
{
    public function getAll(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page') ?: 12;
        $sort = $request->query('sort');

        $result = Cat::when($search, function (Builder $query, string $search) {
            return $query->where('name', 'like', "%$search%");
        })
            ->when(
                $sort,
                function (Builder $query, string $sort) {
                    if ($sort === 'id_asc' || $sort === 'id_desc') {
                        $direction = $sort === 'id_asc' ? 'asc' : 'desc';

                        return $query->orderBy('id', $direction);
                    }

                    if ($sort === 'age_asc' || $sort === 'age_desc') {
                        $direction = $sort === 'age_asc' ? 'asc' : 'desc';

                        return $query->orderByRaw("ISNULL(date_of_birth), date_of_birth $direction");
                    }

                    if ($sort === 'sponsors_asc' || $sort === 'sponsors_desc') {
                        $direction = $sort === 'sponsors_asc' ? 'asc' : 'desc';

                        return $query->orderBy('sponsorships_count', $direction);
                    }

                    return $query;
                },
                // If no sort is provided (when user first lands on the page)
                function (Builder $query) {
                    return $query
                        ->orderByRaw('(is_group = TRUE) DESC') // place is_group=TRUE items first
                        ->orderByRaw('CASE WHEN is_group = TRUE THEN id END ASC') // order items with is_group=TRUE by id ASC
                        ->orderBy('id', 'desc');
                }
            )
            ->paginate($perPage);

        return response()->json($result);
    }

    public function getOne(Cat $cat): JsonResponse
    {
        return response()->json($cat);
    }

    public function getSponsors(Cat $cat): JsonResponse
    {
        $cat->loadMissing('sponsorships.sponsor');

        $viewData = SponsorListViewParser::prepareViewData($cat->sponsorships);

        return response()->json($viewData);
    }
}

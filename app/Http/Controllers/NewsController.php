<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    public function getAll(): JsonResponse
    {
        $paginator = News::orderBy("id", "desc")->paginate(10);

        return response()->json($paginator);
    }
}

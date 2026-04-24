<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FilmLocationsController extends Controller
{
    public function __invoke(Request $request, Film $film): JsonResponse
    {
        $film->load([
            'locations' => fn ($q) => $q->with('user')->orderByDesc('upvotes_count'),
        ]);

        return response()->json([
            'film' => [
                'id' => $film->id,
                'title' => $film->title,
                'release_year' => $film->release_year,
                'synopsis' => $film->synopsis,
            ],
            'locations' => $film->locations->map(fn ($loc) => [
                'id' => $loc->id,
                'name' => $loc->name,
                'city' => $loc->city,
                'country' => $loc->country,
                'description' => $loc->description,
                'upvotes' => $loc->upvotes_count,
                'added_by' => $loc->user?->name,
            ]),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\JsonResponse;

class PublicFilmsController extends Controller
{
    public function index(): JsonResponse
    {
        $films = Film::withCount('locations')
            ->orderBy('title')
            ->get()
            ->map(fn ($film) => [
                'id' => $film->id,
                'title' => $film->title,
                'release_year' => $film->release_year,
                'synopsis' => $film->synopsis,
                'locations_count' => $film->locations_count,
            ]);

        return response()->json([
            'films' => $films,
        ]);
    }

    public function locations(Film $film): JsonResponse
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

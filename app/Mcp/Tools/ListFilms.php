<?php

namespace App\Mcp\Tools;

use App\Models\Film;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Liste tous les films disponibles dans CineMap avec leur année de sortie et le nombre d\'emplacements de tournage.')]
class ListFilms extends Tool
{
    /**
     * Exécuté quand l'IA appelle cet outil.
     * Pas de paramètres nécessaires → on retourne juste tous les films.
     */
    public function handle(Request $request): Response
    {
        $films = Film::withCount('locations')
            ->orderBy('title')
            ->get();

        // On formate le résultat en texte lisible pour l'IA
        $text = $films->map(fn ($film) => "[ID: {$film->id}] {$film->title} ({$film->release_year})".
            " — {$film->locations_count} emplacement(s)".
            ($film->synopsis ? "\n   Synopsis : {$film->synopsis}" : '')
        )->join("\n\n");

        return Response::text($text ?: 'Aucun film trouvé.');
    }

    /**
     * Pas de paramètres d'entrée pour cet outil.
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}

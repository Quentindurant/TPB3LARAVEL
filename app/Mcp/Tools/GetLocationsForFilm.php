<?php

namespace App\Mcp\Tools;

use App\Models\Film;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Récupère les emplacements de tournage d\'un film donné. Utilise l\'ID obtenu via list_films.')]
class GetLocationsForFilm extends Tool
{
    /**
     * Exécuté quand l'IA appelle cet outil avec un film_id.
     */
    public function handle(Request $request): Response
    {
        // Récupère le paramètre film_id envoyé par l'IA
        $filmId = $request->input('film_id');

        $film = Film::with([
            'locations' => fn ($q) => $q->with('user')->orderByDesc('upvotes_count'),
        ])->find($filmId);

        if (! $film) {
            return Response::text("Film ID {$filmId} introuvable.");
        }

        if ($film->locations->isEmpty()) {
            return Response::text("Le film \"{$film->title}\" n'a pas encore d'emplacements.");
        }

        $header = "📽️ {$film->title} ({$film->release_year})\n\n";

        $body = $film->locations->map(fn ($loc, $i) => ($i + 1).". {$loc->name} — {$loc->city}, {$loc->country}\n".
            '   '.($loc->description ?? 'Pas de description')."\n".
            "   👍 {$loc->upvotes_count} upvote(s) | Ajouté par ".($loc->user?->name ?? 'inconnu')
        )->join("\n\n");

        return Response::text($header.$body);
    }

    /**
     * Déclare que cet outil attend un paramètre film_id (entier, obligatoire).
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'film_id' => $schema->integer('L\'identifiant du film (obtenu via list_films)'),
        ];
    }
}

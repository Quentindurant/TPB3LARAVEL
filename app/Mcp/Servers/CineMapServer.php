<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\GetLocationsForFilm;
use App\Mcp\Tools\ListFilms;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('CineMap MCP')]
#[Version('1.0.0')]
#[Instructions('Serveur MCP CineMap — lecture seule. Permet de lister les films et de consulter leurs emplacements de tournage.')]
class CineMapServer extends Server
{
    protected array $tools = [
        ListFilms::class,
        GetLocationsForFilm::class,
    ];

    protected array $resources = [
        //
    ];

    protected array $prompts = [
        //
    ];
}

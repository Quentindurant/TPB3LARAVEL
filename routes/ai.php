<?php

use App\Mcp\Servers\CineMapServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::web('/mcp/cinemap', CineMapServer::class);

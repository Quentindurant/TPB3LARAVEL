<?php

namespace App\Http\Controllers;

use App\Jobs\RecalculateUpvotes;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function upvote(Request $request, Location $location): RedirectResponse
    {
        RecalculateUpvotes::dispatch($location);

        return redirect()
            ->route('locations.index')
            ->with('success', 'Location upvote.');
    }
}

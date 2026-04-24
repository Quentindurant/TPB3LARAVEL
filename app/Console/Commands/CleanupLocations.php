<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;

class CleanupLocations extends Command
{
    protected $signature = 'location:cleanup';

    protected $description = 'test suppr +de 14j et 2 upvote';

    public function handle()
    {
        $location = Location::query()
            ->where('created_at', '<', now()->subDays(14))
            ->where('upvotes_count', '<', 2)
            ->get();

        $count = $location->count();
        foreach ($location as $location) {
            $location->delete();

        }

        $this->info("{$count} locations supprimées.");
    }
}

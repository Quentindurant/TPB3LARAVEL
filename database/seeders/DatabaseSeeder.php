<?php

namespace Database\Seeders;

use App\Models\Film;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'caca',
            'email' => 'caca@caca.caca',
            'password' => 'cacacaca',
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'user',
            'email' => 'user@user.user',
            'password' => 'useruser',
            'is_admin' => false,
        ]);

        $user = User::factory(5)->create();

        $film = Film::factory(10)->create();

        $film->each(function ($film) use ($user) {
            Location::factory(rand(1, 5))->create([
                'film_id' => $film->id,
                'user_id' => $user->random()->id,
            ]);
        });
    }
}

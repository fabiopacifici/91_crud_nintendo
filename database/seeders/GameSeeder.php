<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $games = config('db.games');

        foreach ($games as $game) {
            $newGame = new Game();
            $newGame->name = $game['name'];
            $newGame->image = $game['image'];
            $newGame->description = $game['description'];
            $newGame->price = $game['price'];
            $newGame->release_date = $game['release_date'];
            $newGame->platform = $game['platform'];
            $newGame->has_demo = $game['has_demo'];
            $newGame->save();
        }
    }
}

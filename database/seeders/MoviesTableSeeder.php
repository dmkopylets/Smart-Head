<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\Genre;

class MoviesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drama = Genre::where('title', 'Драма')->first();
        $comedy = Genre::where('title', 'Комедія')->first();

        Movie::create([
            'title' => 'Хрещений батько',
            'published' => true,
            'poster' => 'https://example.com/poster1.jpg',
            'genres' => [$drama]
        ]);

        Movie::create([
            'title' => 'Шоу Трумана',
            'published' => true,
            'poster' => 'https://example.com/poster2.jpg',
            'genres' => [$drama, $comedy]
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\Genre;

class MoviesTableSeeder extends Seeder
{
    public function run(): void
    {
        $drama = Genre::query()->where('title', 'Драма')->first();
        $comedy = Genre::query()->where('title', 'Комедія')->first();

        $godfather = Movie::query()->create([
            'title' => 'Хрещений батько',
            'published' => false,
            'poster' => 'https://example.com/poster1.jpg',
        ]);
        $godfather->genres()->attach($drama);

        $trumanShow = Movie::query()->create([
            'title' => 'Шоу Трумана',
            'published' => false,
            'poster' => 'https://example.com/poster2.jpg',
        ]);
        $trumanShow->genres()->attach([$drama->id, $comedy->id]);
    }
}

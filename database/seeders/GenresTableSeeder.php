<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenresTableSeeder extends Seeder
{

    private array $genres = [
        [
            'title' => 'Драма',
        ],
        [
            'title' => 'Комедія',
        ],
        [
            'title' => 'Фантастика',
        ],
        [
            'title' => 'Навчання',
        ],
    ];
    public function run(): void
    {
        foreach ($this->genres as $genre) {
            Genre::query()->create($genre);
        }
    }
}

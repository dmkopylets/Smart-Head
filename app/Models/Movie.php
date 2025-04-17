<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;

class Movie extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['title', 'published', 'poster'];
    protected $table = 'movies';

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genre');
    }

    public function getDetails(int $movieId): mixed
    {
        return Movie::query()->select(
            'id',
            'title',
            'published',
            'poster',
        )
            ->where('id', $movieId)->first();
    }

    public function getList(
        ? string $title = '',
        ? string $status = '',
        ? string $poster = '',
        ? string $genreIds = '',
    ): Collection
    {
        $baseUrl = url('/api');

        return self::query()
            ->select('movies.id', 'movies.title', 'movies.published', 'movies.poster')
            ->selectRaw('STRING_AGG(movie_genre.genre_id::TEXT, \',\') as genres')
            ->selectRaw("? || '/movies/' || movies.id as details", [$baseUrl])
            ->leftJoin('movie_genre', 'movies.id', '=', 'movie_genre.movie_id')
            ->leftJoin('genres', 'movie_genre.genre_id', '=', 'genres.id')
            ->groupBy('movies.id', 'movies.title')
            ->selectRaw('STRING_AGG(genres.title, \', \') as genres_text')
            ->when($title, function ($query, $title) {
                return $query->where('movies.title', 'like', '%' . $title . '%');
            })
            ->when($genreIds, function ($query, $genreIdsString) {
                return $query->whereIntegerInRaw('movie_genre.genre_id', explode(',', $genreIdsString));
            })
            ->get();
    }
}

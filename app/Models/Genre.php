<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;

class Genre extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['title'];
    protected $table = 'genres';

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_genre');
    }

    public function getDetails(int $genreId): mixed
    {
        $movieId = '';

        $query = self::query()
            ->select('genres.id', 'genres.title')
            ->selectRaw('STRING_AGG(movie_genre.movie_id::TEXT, \',\') as movies')
            ->leftJoin('movie_genre', 'genres.id', '=', 'movie_genre.genre_id')
            ->leftJoin('movies', 'movie_genre.movie_id', '=', 'movies.id')
            ->groupBy('genres.id', 'genres.title')
            ->selectRaw('STRING_AGG(movies.title, \', \') as movies_text')
            ->where('genres.id', $genreId)
            ->when($movieId, function ($query, $movieId) {
                return $query->whereRaw('? = ANY(STRING_TO_ARRAY(STRING_AGG(movie_genre.movie_id::TEXT, \',\')))', [$movieId]);
            });

        return $query->first();
    }

    public function getList(
        ? string $title = '',
        ? string $movieId = '',
    ): Collection
    {
        return self::query()
            ->select('genres.id', 'genres.title')
            ->selectRaw('STRING_AGG(movie_genre.movie_id::TEXT, \',\') as movies')
            ->leftJoin('movie_genre', 'genres.id', '=', 'movie_genre.genre_id')
            ->groupBy('genres.id', 'genres.title')
            ->when($title, function ($query, $title) {
                return $query->where('genres.title', 'like', '%' . $title . '%');
            })
            ->when($movieId, function ($query, $movieId) {
                return $query->whereRaw('? = ANY(STRING_TO_ARRAY(STRING_AGG(movie_genre.movie_id::TEXT, \',\')))', [$movieId]);
            })
            ->get();
    }
}

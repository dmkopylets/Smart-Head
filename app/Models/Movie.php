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
        ? string $genreisIds = '',
    ): Collection
    {
        return self::query()
            ->select('movies.id', 'movies.title', 'movies.published', 'movies.poster')
            ->selectRaw('STRING_AGG(movie_genre.genre_id::TEXT, \',\') as genres')
            ->selectRaw('CONCAT(?, \'/movies/\', movies.id) as details', [url('/api')])
            ->leftJoin('movie_genre', 'movies.id', '=', 'movie_genre.movie_id')
            ->groupBy('movies.id', 'movies.title')
            ->when($title, function ($query, $title) {
                return $query->where('movies.title', 'like', '%' . $title . '%');
            })
            ->when($genreisIds, function ($query, $genreId) {
                return $query->whereRaw('? = ANY(STRING_TO_ARRAY(STRING_AGG(movie_genre.genre_id::TEXT, \',\')))', [$genreId]);
            })
            ->get();
    }
}

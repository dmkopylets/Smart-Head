<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CreateMovieRequest;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class MovieController extends ApiController
{
    public function __construct(Movie $model)
    {
        $this->model = $model;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/movies",
     *     summary="Movies listing",
     *     operationId="getMoviesList",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *         description="part of the title of movie",
     *         name="wanted_Movie_Title",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="status of movie (true or false)",
     *         name="wanted_Movie_Status",
     *         in="query",
     *         @OA\Schema(
     *             type="boolean",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="part of the poster of movie",
     *         name="wanted_Movie_poster",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *          description="id of related genres",
     *          name="genre_id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Movies not found",
     *       ),
     *     @OA\Response(
     *          response="default",
     *          response=200,
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *           )
     *       )
     *  )
     */
    public function index(Request $request): JsonResponse
    {
        $wantedMovieTitle = $request->input('wanted_Movie_Title');
        $wantedMovieStatus = $request->input('wanted_Movie_Status');
        $wantedMoviePoster = $request->input('wanted_Movie_Poster');
        $genresIds = $request->input('wanted_Genre_Ids');
        $moviesList = $this->model->getList($wantedMovieTitle, $wantedMovieStatus, $wantedMoviePoster, $genresIds);
        return response()->json($moviesList, 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/movies/create",
     *     summary="Movie creating",
     *     operationId="createMovie",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *         description="Title of movie",
     *         name="title",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="status",
     *         name="published",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="boolean",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="poster",
     *         name="poster",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="string",
     *         )
     *     ),
     *     @OA\Response(
     *          response="default",
     *          response="201",
     *          description="genre created"
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Validation failed"
     *     )
     * )
     */
    public function create(CreateMovieRequest $request): JsonResponse
    {
        return parent::add($request);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/movies/{id}",
     *     summary="View movie info",
     *     operationId="getMovieById",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *          name="id",
     *          description="Movies id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="{ }")
     * )
     */
    public function show(int $movieId): JsonResponse
    {
        return response()->json(
            $this->model->getDetails($movieId),
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/movie/{id}",
     *     summary="Delete a movie",
     *     operationId="deleteMovie",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *          name="id",
     *          description="Movies id to delete",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Movie not found",
     *     ),
     *     @OA\Response(
     *          response="default",
     *          response="204",
     *          description="Delete genre"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->model::query()->find($id)->delete();
        return $this->sendResponse(null, 'Deleted', 201);
    }
}

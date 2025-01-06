<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateGenreRequest;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenreController extends ApiController
{
    public function __construct(Genre $model)
    {
        $this->model = $model;
    }

    /**
     * @OA\Get(
     *     path="/api/genres",
     *     summary="Genres listing",
     *     operationId="getGenresList",
     *     tags={"Genres"},
     *     @OA\Parameter(
     *         description="part of the title of genre",
     *         name="wanted_Genre_Title",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *          description="id of related films",
     *          name="wanted_MovieId",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Genres not found",
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
        $wantedTitle = $request->input('wanted_Genre_Title');
        $wantedMovieId = $request->input('wanted_MovieId');
        $genresList = $this->model->getList($wantedTitle, $wantedMovieId);
        return response()->json($genresList, 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * @OA\Post(
     *     path="/api/genres/create",
     *     summary="Genre creating",
     *     operationId="createGenre",
     *     tags={"Genres"},
     *     @OA\Parameter(
     *         description="Title of genre",
     *         name="title",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="id of related films",
     *         name="movie_id",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string",
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
    public function create(CreateGenreRequest $request): JsonResponse
    {
        return parent::add($request);
    }

    /**
     * @OA\Get(
     *     path="/api/genres/{id}",
     *     summary="View genre info",
     *     operationId="getGenreById",
     *     tags={"Genres"},
     *     @OA\Parameter(
     *          name="id",
     *          description="Genres id",
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
    public function show(int $genreId): JsonResponse
    {
        return response()->json(
            $this->model->getDetails($genreId),
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/genres/{id}",
     *     summary="Delete a genre",
     *     operationId="deleteGenre",
     *     tags={"Genres"},
     *     @OA\Parameter(
     *          name="id",
     *          description="Genres id to delete",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Genre not found",
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

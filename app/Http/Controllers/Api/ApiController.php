<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiRequest;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Model;

abstract class ApiController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Info(
     *     title="Laravel Swagger API documentation for test task",
     *     version="1.0.0",
     *     @OA\Contact(
     *         name="Dmytro",
     *         email="dm.kopylets@gmail.com"
     *         )
     * )
     *
     */

    protected Model $model;

    protected function add(ApiRequest $request): mixed
    {
        $data = $request->validated();
        $this->model->fill($data)->push();
        return $this->sendResponse(null, 'Created', 201);
    }
}

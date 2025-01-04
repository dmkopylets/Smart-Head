<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Response;

trait ApiResponse
{
    public function sendResponse($result, $message, $code): mixed
    {
        return Response::json(self::makeResponse($message, $result), $code);
    }

    public function sendError($error, int $code = 400, array $data = []): mixed
    {
        return Response::json(self::makeError($error, $data), $code);
    }

    public static function makeResponse(string $message, ? \Illuminate\Database\Eloquent\Collection $data): mixed
    {
        return [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];
    }

    public static function makeError(string $message, array $data = []): array
    {
        $res = [
            'success' => false,
            'message' => $message,
        ];
        if (!empty($data)) {
            $res['data'] = $data;
        }
        return $res;
    }
}

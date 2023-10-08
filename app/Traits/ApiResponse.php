<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * success response method.
     *
     * @param $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    public function sendResponse($data, ?string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * success response method for paginated data.
     *
     * @param LengthAwarePaginator $dataPaginated
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    public function sendResponsePaginated(LengthAwarePaginator $dataPaginated, ?string $message = null, int $code = 200): JsonResponse
    {
        $pagination = $dataPaginated->toArray();
        unset($pagination['data']);
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $dataPaginated->items(),
            'pagination' => $pagination
        ], $code);
    }

    /**
     * return error response.
     *
     * @param $message
     * @param int $code
     * @param array $headers
     * @param array|null $data
     * @return JsonResponse
     */
    public function sendError($message, int $code, ?array $data = null, array $headers = []): JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => $data,
            'code' => $code
        ], $code, $headers);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class Controller
{
    protected function success(mixed $data = null, ?string $message = null, int $code = Response::HTTP_OK): JsonResponse
    {
        $message ??= __('messages.success');

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function error(mixed $errors = null, ?string $message = null, int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $message ??= __('messages.error');

        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    protected function getInputFilters(): array
    {
        return (array) $this->getRequest()->input('filters');
    }

    protected function getRequest(): Request
    {
        return app(Request::class);
    }
}

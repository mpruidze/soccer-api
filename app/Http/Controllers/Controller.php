<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

abstract class Controller
{
    protected function response(mixed $data = null, ?string $message = null, int $code = Response::HTTP_OK): JsonResponse
    {
        $message ??= __('messages.success');

        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if ($data instanceof ResourceCollection && method_exists($data, 'with')) {
            $additionalInfo = $data->with($this->getRequest());

            foreach (['meta', 'links'] as $key) {
                if (isset($additionalInfo[$key])) {
                    $response[$key] = $additionalInfo[$key];
                }
            }
        }

        return response()->json($response, $code);
    }

    protected function getInputFilters(): array
    {
        return (array) $this->getRequest()->input('filters');
    }

    protected function getInputPage(): int
    {
        return (int) $this->getRequest()->input('page', 1);
    }

    protected function getInputPerPage(): ?int
    {
        $perPage = $this->getRequest()->input('perPage');

        return $perPage ? (int) $perPage : 40;
    }

    protected function getRequest(): Request
    {
        return app(Request::class);
    }
}

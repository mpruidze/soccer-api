<?php

declare(strict_types=1);

namespace App\Http\Resources\Transfers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransferCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->toArray();
    }

    public function with(Request $request): array
    {
        return [
            'meta' => [
                'total' => $this->resource->total(),
                'perPage' => $this->resource->perPage(),
                'currentPage' => $this->resource->currentPage(),
                'lastPage' => $this->resource->lastPage(),
            ],
            'links' => [
                'first' => $this->resource->url(1),
                'last' => $this->resource->url($this->lastPage()),
                'prev' => $this->resource->previousPageUrl(),
                'next' => $this->resource->nextPageUrl(),
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Resources\Teams;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResourceWithoutAdditionalInfo extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'country' => $this->getCountry(),
        ];
    }
}

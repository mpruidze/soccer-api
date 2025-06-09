<?php

declare(strict_types=1);

namespace App\Http\Resources\Teams;

use App\Http\Resources\Players\PlayerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'country' => $this->getCountry(),
            'budget' => $this->getBudget(),
            'value' => $this->getSumValue(),
            'players' => PlayerResource::collection($this->whenLoaded('players')),
        ];
    }
}

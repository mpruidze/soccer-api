<?php

declare(strict_types=1);

namespace App\Http\Resources\Players;

use App\Http\Resources\Teams\TeamResourceWithoutRelations;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'age' => $this->age,
            'country' => $this->country,
            'position' => $this->getTranslatedPosition(),
            'value' => $this->value,
            'team' => new TeamResourceWithoutRelations($this->whenLoaded('team')),
        ];
    }
}

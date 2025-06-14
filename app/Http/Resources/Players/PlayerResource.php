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
            'id' => $this->getId(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'age' => $this->getAge(),
            'country' => $this->getCountry(),
            'position' => $this->getTranslatedPosition(),
            'value' => $this->getValue(),
            'team' => new TeamResourceWithoutRelations($this->whenLoaded('team')),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use App\Http\Resources\Teams\TeamResourceWithoutRelations;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'team' => new TeamResourceWithoutRelations($this->whenLoaded('team')),
        ];
    }
}

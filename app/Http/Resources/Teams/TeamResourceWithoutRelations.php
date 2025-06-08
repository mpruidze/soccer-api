<?php

declare(strict_types=1);

namespace App\Http\Resources\Teams;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResourceWithoutRelations extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'budget' => $this->budget,
            'value' => $this->getSumValue(),
        ];
    }
}

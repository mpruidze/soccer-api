<?php

declare(strict_types=1);

namespace App\Http\Resources\Transfers;

use App\Http\Resources\Players\PlayerResourceWithoutAdditionalInfo;
use App\Http\Resources\Teams\TeamResourceWithoutAdditionalInfo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'player' => new PlayerResourceWithoutAdditionalInfo($this->whenLoaded('player')),
            'price' => $this->getPrice(),
            'isTransferred' => $this->getIsTransferred(),
            'fromTeam' => new TeamResourceWithoutAdditionalInfo($this->whenLoaded('fromTeam')),
            'toTeam' => new TeamResourceWithoutAdditionalInfo($this->whenLoaded('toTeam')),
        ];
    }
}

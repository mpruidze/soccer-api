<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Players\PlayerUpdateRequest;
use App\Http\Resources\Players\PlayerResource;
use App\Models\Player;
use App\Services\PlayersService;
use Illuminate\Http\JsonResponse;

class PlayersController extends Controller
{
    public function __construct(private readonly PlayersService $playersService) {}

    public function show(Player $player): JsonResponse
    {
        $player = $this->playersService->find($player);

        return $this->response(new PlayerResource($player));
    }

    public function update(PlayerUpdateRequest $request, Player $player): JsonResponse
    {
        $player = $this->playersService->update($player, $request->validated());

        return $this->response(new PlayerResource($player));
    }
}

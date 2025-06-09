<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\PlayersRepositoryContract;
use App\Models\Player;

class PlayersService extends Service
{
    public function __construct(
        private readonly PlayersRepositoryContract $playersRepository,
    ) {}

    public function find(Player $player): Player
    {
        $this->authorize('view', $player);

        return $this->playersRepository->find($player);
    }

    public function update(Player $player, array $data): Player
    {
        $this->authorize('update', $player);

        return $this->playersRepository->update($player, $data);
    }
}

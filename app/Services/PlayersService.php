<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\PlayersRepositoryContract;
use App\Enums\PlayerPosition;
use App\Models\Player;
use App\Models\Team;

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

    public function generatePlayers(Team $team): void
    {
        $data = [
            PlayerPosition::GOALKEEPER->value => 3,
            PlayerPosition::DEFENDER->value => 6,
            PlayerPosition::MIDFIELDER->value => 6,
            PlayerPosition::ATTACKER->value => 5,
        ];

        foreach ($data as $position => $count) {
            Player::factory()
                ->for($team)
                ->count($count)
                ->create([
                    'position' => $position,
                ]);
        }
    }
}

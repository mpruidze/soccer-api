<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\PlayersRepositoryContract;
use App\Models\Player;

class PlayersRepository implements PlayersRepositoryContract
{
    public function find(Player $player): Player
    {
        $player->load('team');

        return $player;
    }

    public function update(Player $player, array $data): Player
    {
        tap($player)->update($data);

        return $player->load('team');
    }
}

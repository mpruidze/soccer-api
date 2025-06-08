<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Player;

interface PlayersRepositoryContract
{
    public function find(Player $player): Player;

    public function update(Player $player, array $data): Player;
}

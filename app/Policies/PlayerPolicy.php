<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Player;
use App\Models\User;

class PlayerPolicy
{
    public function view(User $user, Player $player): bool
    {
        return $user->team->id === $player->team->id;
    }

    public function update(User $user, Player $player): bool
    {
        return $user->team->id === $player->team->id;
    }
}

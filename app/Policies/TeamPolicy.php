<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    public function view(User $user, Team $team): bool
    {
        return $user->id === $team->user_id;
    }

    public function update(User $user, Team $team): bool
    {
        return $user->id === $team->user_id;
    }
}

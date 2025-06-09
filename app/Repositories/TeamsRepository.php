<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\TeamsRepositoryContract;
use App\Models\Team;

class TeamsRepository implements TeamsRepositoryContract
{
    public function update(Team $team, array $data): Team
    {
        tap($team)->update($data);

        return $team->load('players');
    }

    public function find(Team $team): Team
    {
        $team->load('players');

        return $team;
    }

    public function changeBudget(Team $team, float $amount, bool $increase = true): void
    {
        if ($increase) {
            $team->increment('budget', $amount);
        } else {
            $team->decrement('budget', $amount);
        }
    }
}

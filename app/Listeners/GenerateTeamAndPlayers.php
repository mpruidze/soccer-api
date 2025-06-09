<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\PlayerPosition;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Auth\Events\Registered;

class GenerateTeamAndPlayers
{
    public function handle(Registered $event): void
    {
        /** @var \App\Models\User $user */
        $user = $event->user;

        $team = Team::factory()->for($user)->create();

        $playerData = [
            PlayerPosition::GOALKEEPER->value => 3,
            PlayerPosition::DEFENDER->value => 6,
            PlayerPosition::MIDFIELDER->value => 6,
            PlayerPosition::ATTACKER->value => 5,
        ];

        foreach ($playerData as $position => $count) {
            Player::factory()
                ->for($team)
                ->count($count)
                ->create([
                    'position' => $position,
                ]);
        }
    }
}

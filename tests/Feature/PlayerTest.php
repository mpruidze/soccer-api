<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PlayerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function should_throw_unauthenticated(): void
    {
        $response = $this->getJson(route('players.show', '9999'));

        $response->assertStatus(401)
            ->assertJson(['message' => __('auth.unauthenticated')]);
    }

    #[Test]
    public function should_throw_not_found(): void
    {
        $this->createAndAuthenticateUser();

        $response = $this->getJson(route('players.show', '9999'));

        $response->assertNotFound()
            ->assertJson(['message' => __('app.item_not_found')]);
    }

    #[Test]
    public function should_show_item(): void
    {
        $user = $this->createAndAuthenticateUser();
        /** @var \App\Models\Team $team */
        $team = $this->createTeamForUser($user)->first();
        /** @var \App\Models\Player $player */
        $player = $this->createPlayerForTeam($team)->first();

        $response = $this->getJson(route('players.show', $player->getId()));

        $response->assertOk()
            ->assertJsonStructure($this->playerStructure());
    }

    #[Test]
    public function should_throw_validation_error_on_update(): void
    {
        $user = $this->createAndAuthenticateUser();
        /** @var \App\Models\Team $team */
        $team = $this->createTeamForUser($user)->first();
        /** @var \App\Models\Player $player */
        $player = $this->createPlayerForTeam($team)->first();

        $response = $this->patchJson(route('players.update', $player->getId()), [
            'first_name' => 123,
        ]);

        $response->assertInvalid(['first_name'])
            ->assertJson(['message' => __('validation.string', ['attribute' => 'first name'])]);
    }

    #[Test]
    public function should_update_item(): void
    {
        $user = $this->createAndAuthenticateUser();
        /** @var \App\Models\Team $team */
        $team = $this->createTeamForUser($user)->first();
        /** @var \App\Models\Player $player */
        $player = $this->createPlayerForTeam($team, [
            'first_name' => 'Old Player first name',
        ])->first();

        $response = $this->patchJson(route('players.update', $player->getId()), [
            'first_name' => 'New Player first Name',
        ]);

        $response->assertOk()
            ->assertJsonStructure($this->playerStructure());
        $this->assertDatabaseHas('players', [
            'id' => $player->getId(),
            'first_name' => 'New Player first Name',
        ]);
    }
}

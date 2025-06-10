<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function should_throw_unauthenticated(): void
    {
        $response = $this->getJson(route('teams.show', '9999'));

        $response->assertStatus(401)
            ->assertJson(['message' => __('auth.unauthenticated')]);
    }

    #[Test]
    public function should_throw_not_found(): void
    {
        $this->createAndAuthenticateUser();

        $response = $this->getJson(route('teams.show', '9999'));

        $response->assertNotFound()
            ->assertJson(['message' => __('app.item_not_found')]);
    }

    #[Test]
    public function should_show_item(): void
    {
        $user = $this->createAndAuthenticateUser();
        /** @var \App\Models\Team $team */
        $team = $this->createTeamForUser($user)->first();

        $response = $this->getJson(route('teams.show', $team->getId()));

        $response->assertOk()
            ->assertJsonStructure($this->teamStructure());
    }

    #[Test]
    public function should_throw_validation_error_on_update(): void
    {
        $user = $this->createAndAuthenticateUser();
        /** @var \App\Models\Team $team */
        $team = $this->createTeamForUser($user)->first();

        $response = $this->patchJson(route('teams.update', $team->getId()), [
            'name' => 123,
        ]);

        $response->assertInvalid(['name'])
            ->assertJson(['message' => __('validation.string', ['attribute' => 'name'])]);
    }

    #[Test]
    public function should_update_item(): void
    {
        $user = $this->createAndAuthenticateUser();
        /** @var \App\Models\Team $team */
        $team = $this->createTeamForUser($user, [
            'name' => 'Old Team Name',
        ])->first();

        $response = $this->patchJson(route('teams.update', $team->getId()), [
            'name' => 'New Team Name',
        ]);

        $response->assertOk()
            ->assertJsonStructure($this->teamStructure());
        $this->assertDatabaseHas('teams', [
            'id' => $team->getId(),
            'name' => 'New Team Name',
        ]);
    }
}

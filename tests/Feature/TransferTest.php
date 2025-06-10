<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TransferTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function should_throw_unauthenticated(): void
    {
        $response = $this->getJson(route('transfers.show', '9999'));

        $response->assertStatus(401)
            ->assertJson(['message' => __('auth.unauthenticated')]);
    }

    #[Test]
    public function should_throw_not_found(): void
    {
        $this->createAndAuthenticateUser();

        $response = $this->getJson(route('transfers.show', '9999'));

        $response->assertNotFound()
            ->assertJson(['message' => __('app.item_not_found')]);
    }

    #[Test]
    public function should_show_item(): void
    {
        $user = $this->createAndAuthenticateUserWithTeamAndPlayers();
        /** @var \App\Models\Transfer $transfer */
        $transfer = $this->createTransfers(user: $user)->first();

        $response = $this->getJson(route('transfers.show', $transfer->getId()));

        $response->assertOk()
            ->assertJsonStructure($this->transferStructure());
    }

    #[Test]
    public function should_throw_validation_error_on_update(): void
    {
        $user = $this->createAndAuthenticateUserWithTeamAndPlayers();
        /** @var \App\Models\Transfer $transfer */
        $transfer = $this->createTransfers(user: $user)->first();

        $response = $this->patchJson(route('transfers.update', $transfer->getId()));

        $response->assertInvalid(['price'])
            ->assertJson(['message' => __('validation.required', ['attribute' => 'price'])]);
    }

    #[Test]
    public function should_update_item(): void
    {
        $user = $this->createAndAuthenticateUserWithTeamAndPlayers();
        /** @var \App\Models\Transfer $transfer */
        $transfer = $this->createTransfers(user: $user, attributes: [
            'price' => '10',
        ])->first();

        $response = $this->patchJson(route('transfers.update', $transfer->getId()), [
            'price' => '9999',
        ]);

        $response->assertOk()
            ->assertJsonStructure($this->transferStructure());
        $this->assertDatabaseHas('transfers', [
            'id' => $transfer->getId(),
            'price' => '9999',
        ]);
    }

    #[Test]
    public function should_throw_validation_error_on_store(): void
    {
        $user = $this->createAndAuthenticateUser();
        /** @var \App\Models\Team $team */
        $team = $this->createTeamForUser($user)->first();
        /** @var \App\Models\Player $player */
        $player = $this->createPlayerForTeam($team)->first();

        $response = $this->postJson(route('transfers.store'), [
            'player_id' => $player->getId(),
        ]);

        $response->assertInvalid(['price'])
            ->assertJson(['message' => __('validation.required', ['attribute' => 'price'])]);
        $this->assertDatabaseMissing('transfers', [
            'player_id' => $player->getId(),
        ]);
    }

    #[Test]
    public function should_store_item(): void
    {
        $user = $this->createAndAuthenticateUser();
        /** @var \App\Models\Team $team */
        $team = $this->createTeamForUser($user)->first();
        /** @var \App\Models\Player $player */
        $player = $this->createPlayerForTeam($team)->first();

        $response = $this->postJson(route('transfers.store'), [
            'player_id' => $player->getId(),
            'price' => '9999',
        ]);

        $response->assertOk()
            ->assertJsonStructure($this->transferStructure());
        $this->assertDatabaseHas('transfers', [
            'player_id' => $player->getId(),
            'price' => '9999',
        ]);
    }

    #[Test]
    public function should_throw_error_on_store_with_random_player(): void
    {
        $user = $this->createAndAuthenticateUser();
        $randomUser = $this->createUsers()->last();
        /** @var \App\Models\Team $randomTeam */
        $this->createTeamForUser($user)->first();
        $randomTeam = $this->createTeamForUser($randomUser)->last();
        /** @var \App\Models\Player $randomPlayer */
        $randomPlayer = $this->createPlayerForTeam($randomTeam)->last();

        $response = $this->postJson(route('transfers.store'), [
            'player_id' => $randomPlayer->getId(),
            'price' => '9999',
        ]);

        $response->assertForbidden()
            ->assertJson(['message' => __('messages.player_doesnt_belong_to_user_team')]);
        $this->assertDatabaseMissing('transfers', [
            'player_id' => $randomPlayer->getId(),
        ]);
    }

    #[Test]
    public function should_throw_error_on_store_with_already_on_transfer_player(): void
    {
        $user = $this->createAndAuthenticateUser();
        /** @var \App\Models\Team $team */
        $team = $this->createTeamForUser($user)->first();
        /** @var \App\Models\Player $player */
        $player = $this->createPlayerForTeam($team)->last();
        $this->createTransfers(user: $user);

        $response = $this->postJson(route('transfers.store'), [
            'player_id' => $player->getId(),
            'price' => '9999',
        ]);

        $response->assertForbidden()
            ->assertJson(['message' => __('messages.player_already_on_transfer_list')]);
    }

    #[Test]
    public function should_throw_unauthenticated_on_items_list(): void
    {
        $this->createTransfers(3);

        $response = $this->getJson(route('transfers.index'));

        $response->assertStatus(401)
            ->assertJson(['message' => __('auth.unauthenticated')]);
    }

    #[Test]
    public function should_return_items_list(): void
    {
        $this->createAndAuthenticateUser();
        $this->createTransfers(3);

        $response = $this->getJson(route('transfers.index'));

        $response->assertOk()
            ->assertJsonStructure($this->transferListStructure());
    }

    #[Test]
    public function should_throw_validation_error_on_confirm_transfer(): void
    {
        $user = $this->createAndAuthenticateUserWithTeamAndPlayers();
        /** @var \App\Models\Transfer $transfer */
        $transfer = $this->createTransfers(user: $user)->first();

        $response = $this->postJson(route('transfers.confirm', $transfer->getId()), [
            'action' => 'random action',
        ]);

        $response->assertInvalid(['action'])
            ->assertJson(['message' => __('validation.enum', ['attribute' => 'action'])]);
    }

    #[Test]
    public function should_throw_error_on_confirm_own_transfer(): void
    {
        $user = $this->createAndAuthenticateUserWithTeamAndPlayers();
        /** @var \App\Models\Transfer $transfer */
        $transfer = $this->createTransfers(user: $user)->first();

        $response = $this->postJson(route('transfers.confirm', $transfer->getId()), [
            'action' => 'confirm',
        ]);

        $response->assertNotFound()
            ->assertJson(['message' => __('app.item_not_found')]);
    }

    #[Test]
    public function should_throw_error_on_already_confirmed_transfer(): void
    {
        $user = $this->createAndAuthenticateUserWithTeamAndPlayers();
        /** @var \App\Models\Transfer $transfer */
        $this->createTransfers(user: $user)->first();

        $randomUser = $this->createUsers()->last();
        $randomTeam = $this->createTeamForUser($randomUser)->last();
        /** @var \App\Models\Player $randomPlayer */
        $this->createPlayerForTeam($randomTeam)->last();
        $randomTransfer = $this->createTransfers(user: $randomUser, attributes: [
            'is_transferred' => true,
        ])->first();

        $response = $this->postJson(route('transfers.confirm', $randomTransfer->getId()), [
            'action' => 'confirm',
        ]);

        $response->assertForbidden()
            ->assertJson(['message' => __('messages.transfer_already_completed')]);
    }

    #[Test]
    public function should_confirm_transfer(): void
    {
        $user = $this->createAndAuthenticateUser();
        /** @var \App\Models\Team $team */
        $team = $this->createTeamForUser($user)->first();
        /** @var \App\Models\Player $player */
        $player = $this->createPlayerForTeam($team)->first();
        $teamBudgetBefore = $team->getBudget();

        $randomUser = $this->createUsers()->last();
        /** @var \App\Models\Team $randomTeam */
        $randomTeam = $this->createTeamForUser($randomUser)->last();
        /** @var \App\Models\Player $randomPlayer */
        $randomTeamBudgetBefore = $randomTeam->getBudget();
        $randomPlayer = $this->createPlayerForTeam($randomTeam)->last();
        $playerValueBefore = $randomPlayer->getValue();
        /** @var \App\Models\Transfer $randomTransfer */
        $randomTransfer = $this->createTransfers(user: $randomUser)->first();
        $transferPrice = $randomTransfer->getPrice();

        $response = $this->postJson(route('transfers.confirm', $randomTransfer->getId()), [
            'action' => 'confirm',
        ]);

        $randomPlayer->refresh();
        $team->refresh();
        $randomTeam->refresh();

        $this->assertGreaterThan($playerValueBefore, $randomPlayer->getValue());
        $this->assertEquals($team->getBudget(), ((float) $teamBudgetBefore - (float) $transferPrice));
        $this->assertEquals($randomTeam->getBudget(), ((float) $randomTeamBudgetBefore + (float) $transferPrice));
        $response->assertOk()
            ->assertJsonStructure($this->transferStructure());
        $this->assertDatabaseHas('transfers', [
            'player_id' => $randomPlayer->getId(),
            'is_transferred' => true,
            'to_team_id' => $team->getId(),
        ]);
    }
}

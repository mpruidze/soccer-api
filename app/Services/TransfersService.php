<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\PlayersRepositoryContract;
use App\Contracts\Repositories\TeamsRepositoryContract;
use App\Contracts\Repositories\TransfersRepositoryContract;
use App\Exceptions\HttpException;
use App\Models\Transfer;
use App\Services\Auth\AuthService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Connection;

class TransfersService extends Service
{
    public function __construct(
        private readonly TransfersRepositoryContract $transfersRepository,
        private readonly AuthService $authService,
        private readonly Connection $db,
        private readonly TeamsRepositoryContract $teamsRepository,
        private readonly PlayersRepositoryContract $playersRepository,
    ) {}

    public function findItems(array $filters = [], int $page = 1, ?int $perPage = null): LengthAwarePaginator
    {
        return $this->transfersRepository->findItems($filters, $page, $perPage);
    }

    public function create(array $data): Transfer
    {
        $this->checkPlayerBelongsToUserTeam($data);

        return $this->transfersRepository->create($data);
    }

    public function find(Transfer $transfer): Transfer
    {
        $this->authorize('view', $transfer);

        return $this->transfersRepository->find($transfer);
    }

    public function update(Transfer $transfer, array $data): Transfer
    {
        $this->authorize('update', $transfer);

        $this->checkTransferStatus($transfer);

        return $this->transfersRepository->update($transfer, $data);
    }

    public function confirm(Transfer $transfer, array $data): Transfer
    {
        $transfer = $this->db->transaction(function () use ($transfer, $data) {
            $this->authorize('confirm', $transfer);

            $this->checkTransferStatus($transfer);
            $this->checkBudgetForTransfer($transfer);
            $transfer = $this->transfersRepository->confirm($transfer, $data);

            $this->teamsRepository->changeBudget($transfer->toTeam, (float) $transfer->getPrice(), false);
            $this->teamsRepository->changeBudget($transfer->fromTeam, (float) $transfer->getPrice());

            $this->playersRepository->increasePlayerValue($transfer->player);

            return $transfer;
        });

        return $transfer;
    }

    private function checkBudgetForTransfer(Transfer $transfer): void
    {
        $user = $this->authService->getAuthUser(['team']);
        $teamBudget = $user->team->getBudget();

        if ($transfer->getPrice() > $teamBudget) {
            throw new HttpException(__('messages.insufficient_funds'), 403);
        }
    }

    private function checkPlayerBelongsToUserTeam(array $data): void
    {
        $user = $this->authService->getAuthUser();
        $player = $user->players()->find((int) $data['player_id']);

        if (! $player) {
            throw new HttpException(__('messages.player_doesnt_belong_to_user_team'), 403);
        }

        if ($player->latestActiveTransfer()->exists()) {
            throw new HttpException(__('messages.player_already_on_transfer_list'), 403);
        }
    }

    private function checkTransferStatus(Transfer $transfer): void
    {
        if ($transfer->getIsTransferred()) {
            throw new HttpException(__('messages.transfer_already_completed'), 403);
        }
    }
}

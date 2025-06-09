<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\TransfersRepositoryContract;
use App\Models\Transfer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TransfersRepository implements TransfersRepositoryContract
{
    public function findItems(array $filters = [], int $page = 1, ?int $perPage = null): LengthAwarePaginator
    {
        $transfers = Transfer::query()
            ->with([
                'player',
                'fromTeam',
                'toTeam',
            ])
            ->activeTransfer()
            ->filterByKeyword($filters)
            ->filterByTeamId($filters);

        $transfers = $transfers->latest('updated_at');

        return $transfers->paginate($perPage, ['*'], 'page', $page);
    }

    public function create(array $data): Transfer
    {
        return Transfer::create($data);
    }

    public function find(Transfer $transfer): Transfer
    {
        $transfer->load([
            'player',
            'fromTeam',
            'toTeam',
        ]);

        return $transfer;
    }

    public function update(Transfer $transfer, array $data): Transfer
    {
        $transfer->update($data);

        return $transfer->load([
            'player',
            'fromTeam',
            'toTeam',
        ]);
    }

    public function confirm(Transfer $transfer, array $data): Transfer
    {
        $transfer->update([
            'is_transferred' => true,
            'to_team_id' => $data['to_team_id'],
        ]);

        return $transfer->load([
            'player',
            'fromTeam',
            'toTeam',
        ]);
    }
}

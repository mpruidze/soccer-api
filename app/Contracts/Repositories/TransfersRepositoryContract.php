<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Transfer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TransfersRepositoryContract
{
    public function findItems(array $filters = [], int $page = 1, ?int $perPage = null): LengthAwarePaginator;

    public function create(array $data): Transfer;

    public function find(Transfer $transfer): Transfer;

    public function update(Transfer $transfer, array $data): Transfer;

    public function confirm(Transfer $transfer, array $data): Transfer;
}

<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Transfer;
use App\Models\User;

class TransferPolicy
{
    public function view(User $user, Transfer $transfer): bool
    {
        if (! $transfer->getIsTransferred()) {
            return true;
        }

        return $transfer->getFromTeamId() === $user->team->getId();
    }

    public function update(User $user, Transfer $transfer): bool
    {
        return $user->team->getId() === $transfer->getFromTeamId();
    }

    public function confirm(User $user, Transfer $transfer): bool
    {
        return $user->team->getId() !== $transfer->getFromTeamId();
    }
}

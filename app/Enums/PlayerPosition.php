<?php

declare(strict_types=1);

namespace App\Enums;

enum PlayerPosition: string
{
    case GOALKEEPER = 'goalkeeper';
    case DEFENDER = 'defender';
    case MIDFIELDER = 'midfielder';
    case ATTACKER = 'attacker';

    public function translate(): string
    {
        return __('messages.positions.'.$this->value);
    }
}

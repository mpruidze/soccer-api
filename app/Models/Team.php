<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    protected $fillable = ['name', 'country'];

    protected $casts = [
        'budget' => 'decimal:2',
    ];

    protected $attributes = [
        'budget' => 5000000.00,
    ];

    public function getSumValue(): string
    {
        $sumValue = $this->players->sum(fn ($player) => (float) $player->value);

        return number_format($sumValue, 2, '.', '');
    }

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }
}
